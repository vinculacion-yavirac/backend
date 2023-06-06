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
        Schema::create('integrantes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('solicitude_id')->unsigned()->nullable();
            $table->foreign('solicitude_id')->references('id')->on('solicitudes');

            $table->integer('briefcase_id')->unsigned()->nullable();
            $table->foreign('briefcase_id')->references('id')->on('briefcases');

            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects');

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
