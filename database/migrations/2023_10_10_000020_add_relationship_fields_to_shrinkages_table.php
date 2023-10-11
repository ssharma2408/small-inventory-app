<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToShrinkagesTable extends Migration
{
    public function up()
    {
        Schema::table('shrinkages', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_9094817')->references('id')->on('products');
            $table->unsignedBigInteger('added_by_id')->nullable();
            $table->foreign('added_by_id', 'added_by_fk_9094821')->references('id')->on('users');
        });
    }
}
