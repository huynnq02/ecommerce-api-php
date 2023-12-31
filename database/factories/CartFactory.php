<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition()
    {
        $discount = Discount::inRandomOrder()->firstOrFail(); // Get a random existing discount
        $customer = Customer::inRandomOrder()->firstOrFail(); // Get a random existing customer
        return [
            'customer_id' => $customer->customer_id,
            'discount_id' => $discount->discount_id,
            // 'total_price' => $this->faker->randomFloat(2, 50, 500),
            'total_price' => 0,

        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Cart $cart) {
            CartDetail::factory()
                ->for($cart)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'cart_id' => $cart->cart_id]);

            $cart->update([
                'total_price' => $cart->cartDetails->sum(function (CartDetail $cartDetail) {
                    $product = Product::findOrFail($cartDetail->product_id);

                    return $product ? $cartDetail->quantity * $product->price : 0;
                }),
            ]);
        });
    }
}
