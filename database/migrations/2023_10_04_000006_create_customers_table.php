<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('address');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
