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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id'); // Khai báo khóa chính
            $table->unsignedBigInteger('account_id');
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->json('address')->nullable();
            $table->timestamps(); // Tắt timestamps

            // Ràng buộc khóa ngoại
            $table->foreign('account_id')->references('account_id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
