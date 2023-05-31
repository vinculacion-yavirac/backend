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
        Schema::create('co_project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_general_data')->unsigned()->unsigned()->nullable();
            $table->integer('id_foundations')->unsigned()->nullable();
            $table->integer('id_integrantes')->unsigned()->nullable();
            $table->integer('id_activities')->unsigned()->nullable();
            $table->integer('id_wokplan')->unsigned()->nullable();
            $table->integer('id_firmas')->unsigned()->nullable();
            $table->integer('id_bibliography')->unsigned()->nullable();
            $table->integer('id_anexos')->unsigned()->nullable();
            $table->integer('id_observaciones')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_project');
    }
};
