<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidersTable extends Migration
{
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slider_text')->nullable();
            $table->string('slider_img_url')->nullable();
            $table->integer('slider_order')->nullable();
            $table->string('slider_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
