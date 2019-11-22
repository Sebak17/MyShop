<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['CREATED', 'UNPAID', 'PAID', 'REALIZE', 'SENT']);
            $table->float('cost', 8, 2);
            $table->json('buyer_info');
            $table->string('deliver_name', 24);
            $table->json('deliver_info');
            $table->enum('payment', ['PAYU', 'PAYPAL', 'PAYMENTCARD']);
            $table->string('note')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
