<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $perPage = request('limit', 10);
        $brandId = Brand::where('name', request('brand'))->value('id');
        $categoryId = Category::where('name', request('category'))->value('id');

        $products = Product::with('category', 'brand')
            ->when($brandId, fn($q) => $q->where('brand_id', $brandId))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->paginate($perPage);

        return response()->json($products);
    }


    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // Check if the item already exists in the cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            // If the item exists, update the quantity
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // If the item does not exist, create a new cart item
            Cart::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully!',
        ], 200);
    }
}
