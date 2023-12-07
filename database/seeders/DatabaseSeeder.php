<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Review;
use App\Models\Account;
use App\Models\Inquiry;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\CartDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Customer::factory()->count(20)->created(); // Create customer and account data
        Employee::factory()->count(20)->created();
        Category::factory()->count(10)->create();
        Discount::factory()->count(20)->create();
        Product::factory() // Create product data from existing category data
            ->count(40)
            ->create();
        Cart::factory()->count(20)->create();
        Order::factory()->count(20)->create();
        Invoice::factory()->count(20)->create();
        Supplier::factory()->count(10)->create();
        Warehouse::factory()->count(20)->create();
        Review::factory()->count(20)->create();
        Inquiry::factory()->count(20)->create();
    }
}
