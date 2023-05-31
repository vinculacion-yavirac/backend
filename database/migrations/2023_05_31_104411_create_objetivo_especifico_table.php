<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objetivo_especifico', function (Blueprint $table) {
            $table->string('objetivo_especifico')->nullable();
            $table->string('indicadores_verificables')->nullable();
            $table->string('medio_verificacion')->nullable();
            $table->integer('id')->primary();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('objetivo_especifico');
    }
};
