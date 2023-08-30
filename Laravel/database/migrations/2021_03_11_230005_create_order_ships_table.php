<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_ships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->index();
            $table->integer('unit_id')->index();
            $table->integer('service_id')->index();
            $table->integer('store_id')->index();
            $table->string('code');
            $table->string('status');
            $table->integer('status_id');
            $table->integer('total_fee');
            $table->string('expected_delivery_time')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_printed')->default(false);
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
        Schema::dropIfExists('order_ships');
    }
}
