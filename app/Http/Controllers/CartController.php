<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function updateQuantity(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::find($validated['cart_id']);

        if ($cartItem) {
            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();

            return response()->json(['message' => 'Quantity updated successfully', 'cart' => $cartItem]);
        } else {
            return response()->json(['message' => 'Cart item not found'], 404);
        }
    }

    public function listCartProducts()
    {
        $userId = Auth::id();

        $cart = Cart::where('user_id', $userId)->with('product.brand', 'product.category')->get();

        return response()->json($cart);
    }
}
