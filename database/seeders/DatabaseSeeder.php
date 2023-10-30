<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Category;
use App\Models\Customer;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Account::factory()
        //     ->has(Customer::factory(), 'customer')
        //     ->count(10)
        //     ->create();
        Product::factory()->count(10)->create();
        // Category::factory()->count(10)->create();

    }
}

