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
        Schema::create('datos_generales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo_proyecto')->nullable();
            $table->string('ciclo')->nullable();
            $table->string('cobertura_localizacion')->nullable();
            $table->integer('carrera')->nullable();
            $table->integer('modalidad')->nullable();
            $table->date('fecha')->nullable();
            $table->string('plazo_ejecucion')->nullable();
            $table->string('financiamiento')->nullable();
            $table->date('vigencia_convenio')->nullable();
            $table->date('fecha_presentacion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            $table->string('instituto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_generales');
    }
};
