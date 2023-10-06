<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->integer('category_order');
			$table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_5348111')->references('id')->on('categories');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
