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
        Schema::create('expense_payment_master', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('supplier_id');
			$table->foreign('supplier_id', 'supplier_fk_9044315')->references('id')->on('suppliers');
			$table->string('invoice_number');
			$table->foreign('invoice_number', 'invoice_fk_9044315')->references('invoice_number')->on('inventories');
			$table->float('expense_total', 15, 2);
			$table->float('expense_paid', 15, 2);
			$table->float('expense_pending', 15, 2);
			$table->string('payment_status');			
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_payment_master');
    }
};
