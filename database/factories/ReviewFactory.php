<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date,
            'product_id' => function () {
                return \App\Models\Product::factory()->create()->product_id;
            },
            'customer_id' => function () {
                return \App\Models\Customer::factory()->create()->customer_id;
            },
            'star' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraph,
        ];
    }
}
