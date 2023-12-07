<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('order_id')->references('order_id')->on('orders');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
