<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Notifications\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Step 1 – Shipping details.
     */
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout.index');
    }

    /**
     * Step 2 – Payment (Stripe sandbox).
     */
    public function payment(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_email'   => 'required|email',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string',
            'shipping_zip'     => 'required|string',
            'shipping_country' => 'required|string',
        ]);

        session(['checkout_shipping' => $request->only([
            'shipping_name', 'shipping_email',
            'shipping_address', 'shipping_city',
            'shipping_zip', 'shipping_country',
        ])]);

        $cart  = session('cart', []);
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product  = Product::findOrFail($productId);
            $total   += $product->effective_price * $item['quantity'];
        }

        return view('checkout.payment', [
            'total'       => $total,
            'stripeKey'   => config('services.stripe.key'),
        ]);
    }

    /**
     * Step 3 – Place order after payment confirmation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_intent' => 'required|string',
        ]);

        $cart     = session('cart', []);
        $shipping = session('checkout_shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('checkout.index');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            $lines = [];

            foreach ($cart as $productId => $item) {
                $product  = Product::lockForUpdate()->findOrFail($productId);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("'{$product->name}' is out of stock.");
                }

                $price   = $product->effective_price;
                $total  += $price * $item['quantity'];
                $lines[] = ['product' => $product, 'quantity' => $item['quantity'], 'price' => $price];
            }

            $order = Order::create([
                'user_id'               => Auth::id(),
                'status'                => Order::STATUS_PENDING,
                'total'                 => $total,
                'payment_method'        => 'stripe',
                'payment_status'        => 'paid',
                'stripe_payment_intent' => $request->payment_intent,
                ...$shipping,
            ]);

            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $line['product']->id,
                    'quantity'   => $line['quantity'],
                    'price'      => $line['price'],
                ]);

                $line['product']->decrement('stock', $line['quantity']);
            }

            DB::commit();

            session()->forget(['cart', 'checkout_shipping']);

            // Send confirmation email
            Auth::user()->notify(new OrderConfirmation($order));

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        return view('checkout.success', compact('order'));
    }
}
