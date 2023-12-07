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
        $customer = Customer::inRandomOrder()->firstOrFail(); // Get a random existing customer
        $employee = Employee::inRandomOrder()->firstOrFail(); // Get a random existing employee

        return [
            'date' => $this->faker->date,
            'star' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->word,
            'customer_id' => $customer->customer_id,
            'employee_id' => $employee->employee_id,
        ];
    }
}
