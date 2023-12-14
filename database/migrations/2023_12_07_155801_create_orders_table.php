<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->float('total_price');
            $table->string('payment_method');
            $table->json('destination');
            $table->date('date');
            $table->string('status');
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

