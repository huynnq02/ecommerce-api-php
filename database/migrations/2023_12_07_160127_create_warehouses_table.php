<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->string('warehouse_name');
            $table->text('image')->nullable();
            $table->json('location');
            $table->text('description')->nullable(); 

            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->foreign('employee_id')->references('employee_id')->on('employees');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
