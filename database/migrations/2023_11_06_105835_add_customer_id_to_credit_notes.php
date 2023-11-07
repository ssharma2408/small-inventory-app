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
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
			$table->foreign('customer_id', 'customer_fk_9044488')->references('id')->on('customers');
        });
    } 
};
