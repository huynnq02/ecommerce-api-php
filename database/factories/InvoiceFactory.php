<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date,
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'employee_id' => function () {
                return \App\Models\Employee::factory()->create()->employee_id;
            },
            'customer_id' => function () {
                return \App\Models\Customer::factory()->create()->customer_id;
            },
            'discount_id' => function () {
                return \App\Models\Discount::factory()->create()->discount_id;
            },
        ];
    }
}
