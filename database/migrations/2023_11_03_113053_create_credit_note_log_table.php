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
        Schema::create('credit_note_log', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('credit_order_id')->nullable();
			$table->foreign('credit_order_id', 'order_fk_9044494')->references('id')->on('orders');
			$table->unsignedBigInteger('debit_order_id')->nullable();
			$table->foreign('debit_order_id', 'order_fk_9044495')->references('id')->on('orders');
			$table->unsignedBigInteger('customer_id');
			$table->foreign('customer_id', 'customer_fk_9044493')->references('id')->on('customers');
			$table->float('amount', 15, 2)->nullable();
			$table->float('balance', 15, 2)->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_note_log');
    }
};
