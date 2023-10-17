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
        Schema::table('expense_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->foreign('expense_id', 'expense_fk_9098339')->references('id')->on('inventories');
        });
    }
   
};
