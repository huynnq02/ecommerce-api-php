<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeThisYear,
            'employee_id' => function () {
                return \App\Models\Employee::factory()->create()->employee_id;
            },
            'customer_id' => function () {
                return \App\Models\Customer::factory()->create()->customer_id;
            },
            'star' => $this->faker->numberBetween(1, 5),
            'content' => $this->faker->paragraph,
            // ...Thêm các trường và giá trị mẫu khác
        ];
    }
}
