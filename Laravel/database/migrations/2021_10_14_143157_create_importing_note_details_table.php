<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportingNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importing_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('note_id')->index();
            $table->string('name');
            $table->integer('product_id')->index();
            $table->string('product_code');
            $table->integer('warehouse_id')->index();
            $table->string('warehouse_code');
            $table->integer('price')->default(0);
            $table->string('size');
            $table->string('color');
            $table->integer('quantity');
            $table->double('weight', 20, 2);
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
        Schema::dropIfExists('importing_note_details');
    }
}
