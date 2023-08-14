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
            $table->integer('inventory_product_id')->index()->nullable();
            $table->integer('product_id')->index()->nullable();
            $table->string('product_code')->nullable();
            $table->string('size')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('price')->default(0);
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
