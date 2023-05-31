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
        Schema::table('co_project', function (Blueprint $table) {
            $table->foreign(['id'], 'co_project_projects')->references(['id'])->on('projects');
            $table->foreign(['id_general_data'], 'co_project_general_data')->references(['id'])->on('datos_generales');
            $table->foreign(['id_foundations'], 'co_project_foundations')->references(['id'])->on('foundations');
            $table->foreign(['id_integrantes'], 'co_project_integrantes_has_people')->references(['id'])->on('integrantes_has_people');
            $table->foreign(['id_activities'], 'co_project_activities')->references(['id'])->on('activities');
            $table->foreign(['id_wokplan'], 'co_project_plan_de_trabajo')->references(['id'])->on('plan_de_trabajo');
            $table->foreign(['id_firmas'], 'co_project_firma_has_people')->references(['id'])->on('firma_has_people');
            $table->foreign(['id_bibliography'], 'co_project_bibliography')->references(['id'])->on('bibliography');
            $table->foreign(['id_anexos'], 'co_project_anexo_has_archivo')->references(['id'])->on('anexo_has_archivos');
            $table->foreign(['id_observaciones'], 'co_project_observaciones')->references(['id'])->on('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_project', function (Blueprint $table) {
            $table->dropForeign('co_project_projects');
            $table->dropForeign('co_project_general_data');
            $table->dropForeign('co_project_foundations');
            $table->dropForeign('co_project_integrantes_has_people');
            $table->dropForeign('co_project_activities');
            $table->dropForeign('co_project_plan_de_trabajo');
            $table->dropForeign('co_project_firma_has_people');
            $table->dropForeign('co_project_bibliography');
            $table->dropForeign('co_project_anexo_has_archivo');
            $table->dropForeign('co_project_observaciones');
        });
    }
};
