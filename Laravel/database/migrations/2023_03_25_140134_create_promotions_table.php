<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('summary')->nullable();
            $table->string('image')->nullable();
            $table->double('min_order_value', 20, 2);
            $table->double('discount_value', 20, 2);
            $table->double('discount_percent', 20, 2);
            $table->double('discount_same', 20, 2);
            $table->date('expired_date');
            $table->string('type');
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('promotions');
    }
}
