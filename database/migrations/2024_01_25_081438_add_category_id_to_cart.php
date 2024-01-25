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
        Schema::table('cart', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_5348111')->references('id')->on('categories');
			$table->unsignedBigInteger('sub_category_id')->nullable();
            $table->foreign('sub_category_id', 'category_fk_5348119')->references('id')->on('categories'); 
			$table->unsignedBigInteger('sales_manager_id')->nullable();
            $table->foreign('sales_manager_id', 'sales_manager_fk_9044492')->references('id')->on('users');			
        });
    }    
};
