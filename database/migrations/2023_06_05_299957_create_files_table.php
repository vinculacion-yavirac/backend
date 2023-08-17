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
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id')->comment('ID único del archivo.');

            $table->string('name', 100)->comment('Nombre del archivo.');

            $table->string('type', 200)->comment('Tipo del archivo.');

            $table->longText('content')->comment('Contenido del archivo.');

            $table->string('observation', 200)->comment('Observación del archivo.');

            $table->boolean('state')->comment('Estado del archivo.');

            $table->integer('size')->comment('Tamaño del archivo.');

            $table->integer('briefcase_id')->unsigned()->nullable()->comment('ID de la carpeta asociada.');
            $table->foreign('briefcase_id')->references('id')->on('briefcases');

            $table->integer('document_id')->unsigned()->nullable()->comment('ID del documento asociado.');
            $table->foreign('document_id')->references('id')->on('documents');

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
        Schema::dropIfExists('files');
    }
};
