<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payment_master', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('customer_id');
			$table->foreign('customer_id', 'customer_fk_9044315')->references('id')->on('customers');
			$table->bigInteger('order_number');
			$table->foreign('order_number', 'order_fk_9044315')->references('id')->on('orders');
			$table->float('order_total', 15, 2);
			$table->float('order_paid', 15, 2);
			$table->float('order_pending', 15, 2);
			$table->string('payment_status');			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment_master');
    }
};
