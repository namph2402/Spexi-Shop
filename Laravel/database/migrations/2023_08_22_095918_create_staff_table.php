<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->string('fullname');
            $table->string('phone');
            $table->string('avatar')->nullable();
            $table->date('dob');
            $table->integer('gender');
            $table->string('address');
            $table->string('bank_name')->nullable();
            $table->integer('bank_number')->nullable();
            $table->double('wage', 20, 2)->default(0);
            $table->string('position');
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
        Schema::dropIfExists('staff');
    }
}
