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
        Schema::create('foudation_studen_briefcases', function (Blueprint $table) {
            $table->id();

            $table->integer('foundations')->unsigned()->nullable();
            $table->foreign('foundations')->references('id')->on('foundations');

            $table->integer('solicitudes')->unsigned()->nullable();
            $table->foreign('solicitudes')->references('id')->on('solicitudes');

            $table->integer('projects')->unsigned()->nullable();
            $table->foreign('projects')->references('id')->on('projects');

            $table->integer('briefcases')->unsigned()->nullable();
            $table->foreign('briefcases')->references('id')->on('briefcases');

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
        Schema::dropIfExists('foudation_studen_briefcases');
    }
};
