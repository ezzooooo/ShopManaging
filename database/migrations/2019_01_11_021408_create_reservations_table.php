<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('sales_id')->nullable();
            $table->integer('customer_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('product');
            $table->string('purpose');
            $table->text('memo')->nullable();
            $table->string('stat')->default("예약확정 대기");
            $table->integer('price')->nullable();
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
        Schema::dropIfExists('reservations');
    }
}
