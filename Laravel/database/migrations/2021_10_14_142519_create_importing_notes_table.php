<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportingNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importing_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('creator_id')->index();
            $table->string('creator_name')->index();
            $table->double('total_amount', 20, 2);
            $table->text('description')->nullable();
            $table->dateTime('date_created');
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
        Schema::dropIfExists('importing_notes');
    }
}
