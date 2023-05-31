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
        Schema::create('anexo_has_archivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_anexo')->nullable();
            $table->integer('id_archivo')->unsigned()->nullable();
            $table->integer('tipo_anexo')->nullable();
            $table->foreign(['id_archivo'], 'anexo_has_archivo_files')->references(['id'])->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anexo_has_archivos');
    }
};
