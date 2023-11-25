<?php

namespace Database\Factories;

use App\Models\InvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceDetailFactory extends Factory
{
    protected $model = InvoiceDetail::class;

    public function definition()
    {
        return [
            'product_id' => function () {
                return \App\Models\Product::factory()->create()->product_id;
            },
            'invoice_id' => function () {
                return \App\Models\Invoice::factory()->create()->invoice_id;
            },
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
