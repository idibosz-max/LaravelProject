<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/products
     */
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->active()
            ->when($request->filled('search'),      fn ($q) => $q->search($request->search))
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->category_id))
            ->paginate(15);

        return response()->json($products);
    }

    /**
     * GET /api/products/{id}
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }
}
