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
        Schema::create('cart', function (Blueprint $table) {
            $table->bigInteger('customer_id');
			$table->foreign('customer_id', 'customer_fk_9044316')->references('id')->on('customers');
			$table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_9048502')->references('id')->on('products');
			$table->float('price', 15, 2);
            $table->integer('quantity');
			$table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id', 'tax_fk_9098339')->references('id')->on('taxes');
			$table->tinyInteger('is_box');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
