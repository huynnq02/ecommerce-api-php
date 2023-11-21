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
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id'); // Khai báo khóa chính
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

            // Ràng buộc khóa ngoại
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
