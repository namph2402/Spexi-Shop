<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('user_id')->index();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->text('customer_request')->nullable();
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->double('amount', 20, 2);
            $table->double('shipping_fee', 20, 2);
            $table->double('total_amount', 20, 2);
            $table->double('cod_fee', 20, 2);
            $table->double('discount', 20, 2);
            $table->text('note')->nullable();
            $table->integer('voucher_id')->index()->nullable();
            $table->string('payment_type');
            $table->boolean('payment_status');
            $table->string('order_status')->default('Lên đơn');
            $table->boolean('is_completed')->default(false);
            $table->date("date_created");
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
        Schema::dropIfExists('orders');
    }
}
