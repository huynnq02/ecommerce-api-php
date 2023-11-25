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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id('warehouse_id'); // Khai báo khóa chính
            $table->string('date');
            $table->decimal('total_price', 10, 2);
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('supplier_id');
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

            // Ràng buộc khóa ngoại
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
