<?php

namespace Database\Factories;

use App\Models\WarehouseDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseDetailFactory extends Factory
{
    protected $model = WarehouseDetail::class;

    public function definition()
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit' => $this->faker->word,
        ];
    }
}
