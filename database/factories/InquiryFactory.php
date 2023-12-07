<?php

namespace Database\Factories;

use App\Models\Inquiry;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition()
    {
        $customer = Customer::inRandomOrder()->first(); // Get a random existing customer
        $employee = Employee::inRandomOrder()->first(); // Get a random existing employee

        return [
            'date' => $this->faker->date,
            'star' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraph,
            'customer_id' => $customer->id,
            'employee_id' => $employee->id,
        ];
    }
}
