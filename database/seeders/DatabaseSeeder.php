<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\CartDetail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inquiry;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Account::factory()
            ->has(Customer::factory(), 'customer')
            ->count(10)
            ->create();
        // Product::factory()->count(10)->create();
        // Category::factory()->count(10)->create();
        // Discount::factory()->count(10)->create();
        Cart::factory()->has(CartDetail::factory(), 'cartDetails')->count(10)->create();
        Employee::factory()->count(10)->create();
        Inquiry::factory()->count(10)->create();
        Account::factory()
            ->has(Employee::factory(), 'employee')
            ->count(10)
            ->create();
        // Cart::factory()->has(CartDetail::factory(), 'cartDetails')->count(10)->create();
        // Account::factory()
        //     ->has(Employee::factory(), 'employee')
        //     ->count(10)
        //     ->create();

    }
}
