<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCreditNotesTable extends Migration
{
    public function up()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id', 'order_fk_9178515')->references('id')->on('orders');
        });
    }
}
