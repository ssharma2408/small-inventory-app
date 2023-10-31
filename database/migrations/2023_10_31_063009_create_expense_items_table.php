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
        Schema::create('expense_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_9048501')->references('id')->on('products');
			$table->unsignedBigInteger('expense_id')->nullable();
            $table->foreign('expense_id', 'inventories_fk_9048501')->references('id')->on('inventories');
			$table->integer('stock');
			$table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_5348111')->references('id')->on('categories');
			$table->tinyInteger('is_box');
			$table->float('purchase_price', 15, 2);
			$table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id', 'tax_fk_9098339')->references('id')->on('taxes');
			$table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id', 'category_fk_5348115')->references('id')->on('categories');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_items');
    }
};
