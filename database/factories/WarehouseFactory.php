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
        $employee = Employee::inRandomOrder()->firstOrFail(); // Get a random existing employee
        $supplier = Supplier::inRandomOrder()->firstOrFail(); // Get a random existing supplier

        return [
            'date' => $this->faker->date,
            // 'total_price' => $this->faker->randomFloat(2, 100, 1000),
            'total_price' => 0,

            'employee_id' => $employee->employee_id,
            'supplier_id' => $supplier->supplier_id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Warehouse $warehouse) {
            // Associate Warehouse with WarehouseDetail and get a random existing Product
            WarehouseDetail::factory()
                ->for($warehouse)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'warehouse_id' => $warehouse->warehouse_id]);
            $totalPrice = $warehouse->warehouseDetails->sum(function (WarehouseDetail $warehouseDetail) {
                // Retrieve product_price from the Product model based on product_id
                $product = Product::findOrFail($warehouseDetail->product_id);

                return $product ? $warehouseDetail->quantity * $product->price : 0;
            });

            $warehouse->update([
                'total_price' => $totalPrice,
            ]);
        });
    }
}
