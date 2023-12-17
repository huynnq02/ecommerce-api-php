<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 100);
            $table->float('price', 8, 2);
            $table->string('description', 255);
            $table->string('image', 255);
            $table->json('detail_images')->nullable();
            $table->integer('amount');
            $table->float('rating_average', 8, 2);
            $table->json('specifications');
            $table->json('highlight');
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
            $table->integer('number_of_sold')->default(0); 
            // Add foreign key constraint
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
