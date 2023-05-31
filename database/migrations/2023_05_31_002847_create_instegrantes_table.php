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
        Schema::create('instegrantes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('solicitudes')->unsigned()->nullable();
            $table->foreign('solicitudes')->references('id')->on('solicitudes');

            $table->integer('briefcases')->unsigned()->nullable();
            $table->foreign('briefcases')->references('id')->on('briefcases');

            $table->integer('projects')->unsigned()->nullable();
            $table->foreign('projects')->references('id')->on('projects');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instegrantes');
    }
};
