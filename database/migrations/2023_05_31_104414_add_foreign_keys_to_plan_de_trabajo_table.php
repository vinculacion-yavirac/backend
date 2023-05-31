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
        Schema::table('plan_de_trabajo', function (Blueprint $table) {
            $table->foreign(['id_objetivo_general'], 'plan_de_trabajo_objetivo_general')->references(['id'])->on('objetivo_general');
            $table->foreign(['id_criterio_economico'], 'plan_de_trabajo_criterio_economico')->references(['id'])->on('criterio_economico');
            $table->foreign(['id_objetivo_especifico'], 'plan_de_trabajo_objetivo_especifico')->references(['id'])->on('objetivo_especifico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_de_trabajo', function (Blueprint $table) {
            $table->dropForeign('plan_de_trabajo_objetivo_general');
            $table->dropForeign('plan_de_trabajo_criterio_economico');
            $table->dropForeign('plan_de_trabajo_objetivo_especifico');
        });
    }
};
