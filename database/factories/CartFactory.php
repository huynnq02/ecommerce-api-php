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
        $customer = Customer::factory()->create();
        $discount = Discount::factory()->create();

        return [
            'customer_id' => $customer->customer_id,
            'discount_id' => $discount->discount_id,
            'total_price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }

}
