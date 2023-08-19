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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id')->comment('ID único de la solicitud.');

            $table->timestamp('approval_date')->comment('Fecha de aprobación de la solicitud.');

            $table->unsignedBigInteger('solicitudes_status_id')->nullable()->comment('ID del estado de las solicitudes.');
            $table->foreign('solicitudes_status_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('type_request_id')->nullable()->comment('ID del tipo de solicitud.');
            $table->foreign('type_request_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('project_id')->nullable()->comment('ID del proyecto asociado.');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedInteger('created_by')->nullable()->comment('ID del usuario que creó la solicitud.');
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false)->comment('Indica si la solicitud está archivada.');
            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó la solicitud.');

            $table->unsignedInteger('archived_by')->nullable()->comment('ID del usuario que archivó la solicitud.');
            $table->foreign('archived_by')->references('id')->on('users');

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
        Schema::dropIfExists('solicitudes');
    }
};
