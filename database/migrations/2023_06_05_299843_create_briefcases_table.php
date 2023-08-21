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
        Schema::create('briefcases', function (Blueprint $table) {
            $table->increments('id')->comment('ID único de la carpeta.');

            $table->string('observations')->nullable()->comment('Observaciones de la carpeta.');

            $table->boolean('state')->default(false)->nullable()->comment('Estado de la carpeta (activo/inactivo).');

            $table->boolean('archived')->default(false)->nullable()->comment('Indica si la carpeta está archivada.');
            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó la carpeta.');

            $table->integer('created_by')->unsigned()->nullable()->comment('ID del usuario que creó la carpeta.');
            $table->foreign('created_by')->references('id')->on('users');

            $table->integer('update_by')->unsigned()->nullable()->comment('ID del usuario que actualizó la carpeta.');
            $table->foreign('update_by')->references('id')->on('users');

            $table->integer('archived_by')->unsigned()->nullable()->comment('ID del usuario que archivó la carpeta.');
            $table->foreign('archived_by')->references('id')->on('users');

            $table->integer('project_participant_id')->unsigned()->nullable()->comment('ID del participante del proyecto asociado.');
            $table->foreign('project_participant_id')->references('id')->on('project_participants');

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
        Schema::dropIfExists('briefcases');
    }
};
