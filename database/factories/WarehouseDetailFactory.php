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
            'warehouse_id' => \App\Models\Warehouse::factory(),
            'product_id' => \App\Models\Product::factory(),
            'quantity' => $this->faker->randomNumber(2),
            'unit' => $this->faker->word,
        ];
    }
}
