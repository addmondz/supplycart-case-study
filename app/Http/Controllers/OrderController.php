<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
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

        $order = Order::create(
            [
                'user_id' => Auth::id(),
                'tot' => Auth::id(),
            ]
        );
        $sum = 0;

        foreach ($validated['carts'] as $cartData) {
            $cart = Cart::where('id', $cartData['cart_id'])->first();
            $product = $cart->product;

            $rowSum = $product->price * $cartData['quantity'];

            OrderItems::updateOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_price' => $product->price,
                    'quantity' => $cartData['quantity'],
                    'sum' => $rowSum
                ],
            );

            $sum += $rowSum;

            $cart->delete();
        }

        $order->total_price = $sum;
        $order->save();

        return response()->json(['message' => 'Order created successfully']);
    }

    public function listOrderProducts(Request $request)
    {
        $perPage = request()->query('limit', 10);
        $userId = Auth::id();

        $orders = Order::where('user_id', $userId)
            ->with('items.product.brand', 'items.product.category')
            ->paginate($perPage);

        return response()->json($orders);
    }
}
