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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('inquiry_id'); // Khai báo khóa chính
            $table->string('date');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('star');
            $table->text('content');
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

            // Ràng buộc khóa ngoại
            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
