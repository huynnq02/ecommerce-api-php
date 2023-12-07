<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => [
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
            ],
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
