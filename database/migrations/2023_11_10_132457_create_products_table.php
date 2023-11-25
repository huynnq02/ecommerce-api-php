<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id'); // Khai báo khóa chính
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('amount');
            $table->decimal('rating_average', 3, 2)->nullable();
            $table->json('specifications')->nullable();
            $table->json('highlight')->nullable();
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

            // Ràng buộc khóa ngoại
            $table->foreign('category_id')->references('category_id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
