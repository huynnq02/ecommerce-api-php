<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $customer = Customer::inRandomOrder()->firstOrFail(); 
        $product = Product::inRandomOrder()->firstOrFail(); 

        return [
            'date' => $this->faker->date,
            'star' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->word,
            'product_id' => $product->product_id,
            'customer_id' => $customer->customer_id,
        ];
    }
}
