<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('tax', 15, 2)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
