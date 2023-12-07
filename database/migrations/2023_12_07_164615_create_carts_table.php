<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id'); // Use id() instead of bigIncrements('cart_id')
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->float('total_price');
            $table->timestamps();

            // Define foreign key relationships
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
