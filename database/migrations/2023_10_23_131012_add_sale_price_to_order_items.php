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
        Schema::table('order_items', function (Blueprint $table) {
            $table->tinyInteger('is_box');
			$table->float('sale_price', 15, 2);
			$table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id', 'tax_fk_9098339')->references('id')->on('taxes');
        });
    }

};
