<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'carts' => 'required|array',
            'carts.*.cart_id' => 'required|exists:carts,id',
            'carts.*.quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $discount = User::find($userId)->membershipType->discount;
        $order = Order::create(['user_id' => $userId, 'tot' => $userId]);
        $sum = 0;

        foreach ($validated['carts'] as $cartData) {
            $cart = Cart::find($cartData['cart_id']);
            $product = $cart->product;
            $discountRate = 1 - ($discount / 100);
            $priceAfterDiscount = number_format($product->price * $discountRate, 2);
            $rowSum = (float) $priceAfterDiscount * $cartData['quantity'];

            OrderItems::updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $product->id],
                [
                    'discount' => $discount,
                    'product_original_price' => $product->price,
                    'quantity' => $cartData['quantity'],
                    'product_price_after_discount' => $priceAfterDiscount,
                    'sum' => $rowSum
                ]
            );

            $sum += $rowSum;
            $cart->delete();
        }

        $order->update(['total_price' => $sum]);

        return response()->json(['message' => 'Order created successfully']);
    }

    public function listOrderProducts(Request $request)
    {
        $perPage = $request->query('limit', 10);
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)
            ->with('items.product.brand', 'items.product.category')
            ->paginate($perPage);

        return response()->json($orders);
    }
}
