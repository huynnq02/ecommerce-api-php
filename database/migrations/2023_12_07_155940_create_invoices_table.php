<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->date('date');
            $table->float('total_price');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('discount_id');
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('discount_id')->references('discount_id')->on('discounts');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
