<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShrinkagesTable extends Migration
{
    public function up()
    {
        Schema::create('shrinkages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number');
            $table->date('date');
            $table->longText('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
