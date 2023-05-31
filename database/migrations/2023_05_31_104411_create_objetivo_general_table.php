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
        Schema::create('objetivo_general', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('objetivo')->nullable();
            $table->string('indicador_verificable')->nullable();
            $table->string('metodo_verificacion')->nullable();
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
        Schema::dropIfExists('objetivo_general');
    }
};
