<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date,
            'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'employee_id' => \App\Models\Employee::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
        ];
    }
}
