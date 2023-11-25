<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    public function definition()
    {
        return [
            'product_id' => function () {
                return \App\Models\Product::factory()->create()->product_id;
            },
            'order_id' => function () {
                return \App\Models\Order::factory()->create()->order_id;
            },
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
