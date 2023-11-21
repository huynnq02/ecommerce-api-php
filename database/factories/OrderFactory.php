<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'customer_id' => function () {
                return \App\Models\Customer::factory()->create()->customer_id;
            },
            'total_price' => $this->faker->randomFloat(2, 10, 500),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'cash']),
            'destination' => $this->faker->address,
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered']),
        ];
    }
}
