<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        $employee = Employee::inRandomOrder()->firstOrFail();
        $customer = Customer::inRandomOrder()->firstOrFail();
        $discount = Discount::inRandomOrder()->firstOrFail();
        return [
            'date' => $this->faker->date,
            // 'total_price' => $this->faker->randomFloat(2, 50, 500),
            'total_price' => $this->faker->randomFloat(2, 50, 500), // need to fix this

            'employee_id' => $employee->employee_id,
            'customer_id' => $customer->customer_id,
            'discount_id' => $discount->discount_id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            InvoiceDetail::factory()
                ->for($invoice)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'invoice_id' => $invoice->invoice_id]);
        });
    }
}
