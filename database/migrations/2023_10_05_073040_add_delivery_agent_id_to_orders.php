<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_agent_id')->nullable();
            $table->foreign('delivery_agent_id', 'delivery_agent_fk_9044492')->references('id')->on('users');
        });
    }
};
