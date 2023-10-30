<?php
// CartDetailFactory.php

namespace Database\Factories;

use App\Models\CartDetail;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartDetailFactory extends Factory
{
    protected $model = CartDetail::class;

    public function definition()
    {
        $product = Product::factory()->create();

        return [
            'product_id' => $product->product_id,
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
