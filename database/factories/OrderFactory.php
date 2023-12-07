<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $customer = Customer::inRandomOrder()->firstOrFail(); // Get a random existing customer

        return [
            'customer_id' => $customer->customer_id,
            // 'total_price' => $this->faker->randomFloat(2, 50, 500),
            'total_price' => 0,

            'payment_method' => 'Cash',
            'destination' =>
            json_encode([
                'street' => $this->faker->streetAddress,
                'city' => $this->faker->city,
            ]),
            'date' => $this->faker->date,
            'status' => $this->faker->randomElement(['Processing', 'Shipped', 'Delivered']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            // Associate Order with OrderDetail and get a random existing Product
            OrderDetail::factory()
                ->for($order)
                ->create(['product_id' => Product::inRandomOrder()->firstOrFail()->product_id, 'order_id' => $order->order_id]);
            $order->update([
                'total_price' => $order->orderDetails->sum(function (OrderDetail $orderDetail) {
                    // Retrieve product_price from the Product model based on product_id
                    $product = Product::findOrFail($orderDetail->product_id);
                    return $product ? $orderDetail->quantity * $product->price : 0;
                }),
            ]);
        });
    }
}
