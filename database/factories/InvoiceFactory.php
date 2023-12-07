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
        $employee = Employee::inRandomOrder()->first(); // Get a random existing employee
        $customer = Customer::inRandomOrder()->first(); // Get a random existing customer
        $discount = Discount::inRandomOrder()->first(); // Get a random existing discount

        return [
            'date' => $this->faker->date,
            'total_price' => $this->faker->randomFloat(2, 50, 500),
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'discount_id' => $discount->id,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Invoice $invoice) {
            // Associate Invoice with InvoiceDetail and get a random existing Product
            InvoiceDetail::factory()
                ->for($invoice)
                ->create(['product_id' => Product::inRandomOrder()->first()->id, 'invoice_id' => $invoice->id]);
        });
    }
}
