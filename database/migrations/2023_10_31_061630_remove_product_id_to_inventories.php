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
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('category_id');
            $table->dropColumn('sub_category_id');
            $table->dropColumn('box_or_unit');
            $table->dropColumn('stock');
            $table->dropColumn('purchase_price');
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('box_or_unit');
            $table->integer('stock');
            $table->float('purchase_price');
            $table->unsignedBigInteger('tax_id');
        });
    }
};
