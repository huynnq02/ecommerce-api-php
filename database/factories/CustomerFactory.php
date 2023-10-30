<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'birthday' => $this->faker->date(),
            'address' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
            ],
        ];
    }
}

