<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('invoice_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('invoice_id')->references('invoice_id')->on('invoices');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
