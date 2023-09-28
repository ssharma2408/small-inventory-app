<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name');
            $table->integer('stock');
            $table->float('price', 15, 2);
            $table->string('discount_type');
            $table->float('discount', 15, 2)->nullable();
            $table->float('tax', 15, 2);
            $table->float('final_price', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
