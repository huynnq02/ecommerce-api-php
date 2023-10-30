<?php
// DiscountFactory.php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountFactory extends Factory
{
    protected $model = Discount::class;

    public function definition()
    {
        return [
            'discount_value' => $this->faker->randomFloat(2, 5, 50),
            'code' => $this->faker->unique()->word,
            'start_day' => $this->faker->date,
            'end_day' => $this->faker->date,
        ];
    }
}
