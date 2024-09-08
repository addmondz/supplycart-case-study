<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cart;
use App\Models\MembershipType;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItems;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // Create a user and log them in
        MembershipType::factory()->create();
        $this->user = User::factory()->create();
        Auth::login($this->user);
    }

    public function testCreateOrder()
    {

        // Create a product and cart
        $product = Product::factory()->create(['price' => 100]);
        $cart = Cart::factory()->create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
        ]);

        // Define the request payload
        $payload = [
            'carts' => [
                [
                    'cart_id' => $cart->id,
                    'quantity' => 2
                ]
            ]
        ];

        // Calculate the expected values
        $discount = $this->user->membershipType->discount;
        $discountRate = 1 - ($discount / 100);
        $priceAfterDiscount = number_format($product->price * $discountRate, 2);
        $rowSum = (float) $priceAfterDiscount * 2;

        // Send a POST request to the create endpoint
        $response = $this->postJson('/api/order', $payload);

        // Assert that the response status is 200 and contains the success message
        $response->assertStatus(200)
            ->assertJson(['message' => 'Order created successfully']);

        // Assert that the Order was created
        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals($rowSum, $order->total_price);

        // Assert that the OrderItem was created
        $orderItem = OrderItems::where('order_id', $order->id)->first();
        $this->assertNotNull($orderItem);
        $this->assertEquals($discount, $orderItem->discount);
        $this->assertEquals($product->price, $orderItem->product_original_price);
        $this->assertEquals(2, $orderItem->quantity);
        $this->assertEquals($priceAfterDiscount, $orderItem->product_price_after_discount);
        $this->assertEquals($rowSum, $orderItem->sum);

        // Assert that the cart item was deleted
        $this->assertNull(Cart::find($cart->id));
    }
}
