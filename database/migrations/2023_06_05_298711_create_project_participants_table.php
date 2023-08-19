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
        Schema::create('project_participants', function (Blueprint $table) {
            $table->increments('id')->comment('ID único del participante en el proyecto.');

            $table->json('functions')->nullable()->comment('Funciones del participante.');

            $table->timestamp('assignment_date')->nullable()->comment('Fecha de asignación del participante.');

            $table->unsignedBigInteger('level_id')->nullable()->comment('ID del nivel asociado.');
            $table->foreign('level_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('catalogue_id')->nullable()->comment('ID del catálogo asociado.');
            $table->foreign('catalogue_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('schedule_id')->nullable()->comment('ID del horario asociado.');
            $table->foreign('schedule_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('state_id')->nullable()->comment('ID del estado del participante.');
            $table->foreign('state_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('project_id')->nullable()->comment('ID del proyecto asociado.');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->integer('participant_id')->unsigned()->nullable()->comment('ID del usuario participante.');
            $table->foreign('participant_id')->references('id')->on('users');

            $table->string('role')->nullable()->comment('Rol del participante en el proyecto.');

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
        Schema::dropIfExists('project_participants');
    }
};
