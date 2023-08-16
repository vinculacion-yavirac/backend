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
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('Nombre del Documento: Carta de compromiso, informe de inicio, control de asistencia, registro de asistencia, informe final, control de cumplimiento, certificado, encuesta de percepción, informe de control.');
            $table->string('template', 200)->comment('El documento está guardado en el servidor.');
            $table->boolean('state')->comment('Estado del documento.');
            
            $table->unsignedBigInteger('responsible_id')->nullable()->comment('Rol responsable de completar el documento.');
            $table->foreign('responsible_id')->references('id')->on('roles');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false)->comment('Indica si el documento está archivado.');
            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó el documento.');
            $table->integer('archived_by')->unsigned()->nullable();
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
        Schema::dropIfExists('documents');
    }
};
