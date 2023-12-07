<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id('discount_id');
            $table->float('discount_value');
            $table->string('code');
            $table->date('start_day');
            $table->date('end_day');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
