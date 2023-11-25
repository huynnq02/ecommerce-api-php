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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id('discount_id'); // Khai báo khóa chính
            $table->decimal('discount_value', 5, 2);
            $table->string('code');
            $table->date('start_day');
            $table->date('end_day');
            $table->timestamps(); // Sử dụng timestamps (created_at và updated_at)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
