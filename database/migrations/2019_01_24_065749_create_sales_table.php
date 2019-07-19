<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('reservation_id');
            $table->string('customer_name');
            $table->string('product');
            $table->integer('pre_cash');
            $table->integer('cash');
            $table->integer('card');
            $table->integer('sales');
            $table->string('sales_stat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
