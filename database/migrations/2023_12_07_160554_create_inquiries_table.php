<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiriesTable extends Migration
{
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id('inquiry_id');
            $table->date('date');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('customer_id');
            $table->integer('star');
            $table->string('content', 100);
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employees');
            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
}
