<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name', 100);
            // Add any other columns you need for the 'categories' table
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
