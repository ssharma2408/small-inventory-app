<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNotesTable extends Migration
{
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('amount', 15, 2);
            $table->longText('description')->nullable();
            $table->datetime('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
