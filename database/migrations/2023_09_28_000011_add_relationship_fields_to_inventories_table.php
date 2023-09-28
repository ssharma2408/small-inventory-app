<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToInventoriesTable extends Migration
{
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id', 'supplier_fk_9044315')->references('id')->on('suppliers');
        });
    }
}
