<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition()
    {
        $employee = Employee::inRandomOrder()->firstOrFail();

        return [
            'warehouse_name' => $this->faker->company,
            'image' => $this->faker->imageUrl(),
            'location' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
                'latitude' => $this->faker->latitude,
                'longitude' => $this->faker->longitude,
            ],
            'employee_id' => $employee->employee_id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Warehouse $warehouse) {
            WarehouseDetail::factory()
                ->for($warehouse)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'warehouse_id' => $warehouse->warehouse_id]);
        });
    }
}
