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
        $supplier = Supplier::inRandomOrder()->firstOrFail(); 

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
            WarehouseDetail::factory()
                ->for($warehouse)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'warehouse_id' => $warehouse->warehouse_id]);
            $totalPrice = $warehouse->warehouseDetails->sum(function (WarehouseDetail $warehouseDetail) {
                $product = Product::findOrFail($warehouseDetail->product_id);
                return $product ? $warehouseDetail->quantity * $product->price : 0;
            });

            $warehouse->update([
                'total_price' => $totalPrice,
            ]);
        });
    }
}
