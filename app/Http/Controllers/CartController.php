<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private const SESSION_KEY = 'cart';

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function getCart(): array
    {
        return session(self::SESSION_KEY, []);
    }

    private function saveCart(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    public function index()
    {
        $cart  = $this->getCart();
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal  = $product->effective_price * $item['quantity'];
                $total    += $subtotal;
                $items[]   = [
                    'product'   => $product,
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $subtotal,
                ];
            }
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = $this->getCart();
        $id   = $request->product_id;

        $cart[$id] = [
            'quantity' => ($cart[$id]['quantity'] ?? 0) + $request->quantity,
        ];

        $this->saveCart($cart);

        return back()->with('success', "\"{$product->name}\" added to cart.");
    }

    public function update(Request $request, int $productId)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);

        $cart = $this->getCart();

        if ($request->quantity === 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $request->quantity;
        }

        $this->saveCart($cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);

        return back()->with('success', 'Item removed from cart.');
    }

    /**
     * API – add item (used by React cart component).
     */
    public function apiAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1',
        ]);

        $product  = Product::findOrFail($request->product_id);
        $qty      = $request->get('quantity', 1);
        $cart     = $this->getCart();

        if ($product->stock < $qty) {
            return response()->json(['error' => 'Insufficient stock.'], 422);
        }

        $id = $request->product_id;
        $cart[$id] = ['quantity' => ($cart[$id]['quantity'] ?? 0) + $qty];
        $this->saveCart($cart);

        return response()->json([
            'message'    => 'Added to cart.',
            'cart_count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }

    /**
     * API – get current cart for React preview.
     */
    public function apiIndex()
    {
        $cart  = $this->getCart();
        $items = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $subtotal = $product->effective_price * $item['quantity'];
                $total   += $subtotal;
                $items[]  = [
                    'id'       => $product->id,
                    'name'     => $product->name,
                    'price'    => $product->effective_price,
                    'image'    => $product->image,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}
