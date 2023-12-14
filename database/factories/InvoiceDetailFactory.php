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
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
