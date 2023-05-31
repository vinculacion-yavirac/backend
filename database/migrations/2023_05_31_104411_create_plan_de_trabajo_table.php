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
        Schema::create('plan_de_trabajo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion_general')->nullable();
            $table->string('objetivo')->nullable();
            $table->string('analisis_situacional')->nullable();
            $table->string('justificacion')->nullable();
            $table->string('conclusiones')->nullable();
            $table->string('recomendaciones')->nullable();
            $table->integer('id_objetivo_general')->nullable();
            $table->integer('id_criterio_economico')->nullable();
            $table->integer('id_objetivo_especifico')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_de_trabajo');
    }
};
