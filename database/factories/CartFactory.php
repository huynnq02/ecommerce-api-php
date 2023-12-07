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
        $discount = Discount::inRandomOrder()->first(); // Get a random existing discount
        $customer = Customer::inRandomOrder()->first(); // Get a random existing customer
        return [
            'customer_id' => $customer->id,
            'discount_id' => $discount->id,
            // 'total_price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Cart $cart) {
            // Associate Cart with CartDetail and get a random existing Product
            CartDetail::factory()
                ->for($cart)
                ->create(['product_id' => Product::inRandomOrder()->first()->id, 'cart_id' => $cart->id]);

            $cart->update([
                'total_price' => $cart->cartDetails->sum(function (CartDetail $cartDetail) {
                    // Retrieve product_price from the Product model based on product_id
                    $product = Product::findOrFail($cartDetail->product_id);

                    return $product ? $cartDetail->quantity * $product->price : 0;
                }),
            ]);
        });
    }
}
