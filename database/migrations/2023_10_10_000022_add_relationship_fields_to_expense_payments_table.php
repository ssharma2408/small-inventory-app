<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToExpensePaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('expense_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id', 'invoice_fk_9094866')->references('invoice_number')->on('inventories');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id', 'payment_fk_9094867')->references('id')->on('payment_methods');
        });
    }
}
