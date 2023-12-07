<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->date('date');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('star');
            $table->string('content', 100);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
