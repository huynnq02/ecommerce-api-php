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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id'); // Khai báo khóa chính
            $table->string('date');
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

            // Ràng buộc khóa ngoại
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('discount_id')->references('discount_id')->on('discounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
