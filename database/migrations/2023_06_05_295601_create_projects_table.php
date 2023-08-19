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
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID único del proyecto.');

            $table->string('code', 20)->comment('Código del proyecto.');

            $table->string('name', 200)->comment('Nombre del proyecto.');

            $table->string('name_institute', 200)->comment('Nombre del instituto asociado al proyecto.');

            $table->string('cicle', 50)->comment('Ciclo del proyecto.');

            $table->string('address', 200)->comment('Dirección del proyecto.');

            $table->string('Modality', 50)->comment('Modalidad del proyecto.');

            $table->string('field', 100)->comment('Campo del proyecto.');

            $table->integer('term_execution')->comment('Duración de ejecución en meses.');

            $table->timestamp('start_date')->comment('Fecha de inicio del proyecto.');

            $table->timestamp('end_date')->nullable()->comment('Fecha de finalización del proyecto.');

            $table->timestamp('date_presentation')->comment('Fecha de presentación del proyecto.');

            $table->json('linking_activity')->comment('Actividad de vinculación del proyecto.');

            $table->json('sectors_intervention')->comment('Sectores de intervención del proyecto.');

            $table->json('strategic_axes')->comment('Ejes estratégicos del proyecto.');

            $table->string('objetive', 500)->comment('Objetivo del proyecto.');

            $table->string('description', 500)->comment('Descripción del proyecto.');

            $table->string('situational_analysis', 500)->comment('Análisis situacional del proyecto.');

            $table->string('foundation', 500)->comment('Fundamentación del proyecto.');

            $table->string('justification', 500)->comment('Justificación del proyecto.');

            $table->string('conclusions', 500)->comment('Conclusiones del proyecto.');

            $table->string('recommendation', 500)->comment('Recomendaciones del proyecto.');

            $table->json('direct_beneficiaries')->comment('Beneficiarios directos del proyecto.');

            $table->json('indirect_beneficiaries')->comment('Beneficiarios indirectos del proyecto.');

            $table->string('schedule', 200)->comment('Cronograma del proyecto.');

            $table->json('evaluation_monitoring_strategy')->comment('Estrategia de evaluación y monitoreo del proyecto.');

            $table->json('bibliographies')->comment('Bibliografía del proyecto.');

            $table->json('attached_project')->comment('Proyecto adjunto.');

            $table->unsignedBigInteger('convention_id')->nullable()->comment('ID de la convención asociada.');
            $table->foreign('convention_id')->references('id')->on('conventions');

            $table->unsignedBigInteger('school_period_id')->nullable()->comment('ID del período escolar asociado.');
            $table->foreign('school_period_id')->references('id')->on('school_periods');

            $table->unsignedBigInteger('beneficiary_institution_id')->nullable()->comment('ID de la institución beneficiaria asociada.');
            $table->foreign('beneficiary_institution_id')->references('id')->on('beneficiary_institutions')->onDelete('cascade');

            $table->unsignedBigInteger('career_id')->nullable()->comment('ID de la carrera asociada.');
            $table->foreign('career_id')->references('id')->on('careers');

            $table->unsignedBigInteger('sub_line_investigation_id')->nullable()->comment('ID de la sub-línea de investigación asociada.');
            $table->foreign('sub_line_investigation_id')->references('id')->on('sub_lines_investigations');

            $table->unsignedBigInteger('authorized_by')->nullable()->comment('ID de la persona autorizada.');
            $table->foreign('authorized_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('made_by')->nullable()->comment('ID de la persona que realizó el proyecto.');
            $table->foreign('made_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('approved_by')->nullable()->comment('ID de la persona que aprobó el proyecto.');
            $table->foreign('approved_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('catalogue_id')->nullable()->comment('ID del catálogo asociado.');
            $table->foreign('catalogue_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('state_id')->nullable()->comment('ID del estado del proyecto.');
            $table->foreign('state_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('stateTwo_id')->nullable()->comment('ID del segundo estado del proyecto.');
            $table->foreign('stateTwo_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('frequency_id')->nullable()->comment('ID de la frecuencia asociada.');
            $table->foreign('frequency_id')->references('id')->on('catalogs');

            $table->integer('created_by')->unsigned()->nullable()->comment('Usuario que creó el proyecto.');
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false)->comment('Indica si el proyecto está archivado.');
            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó el proyecto.');

            $table->integer('archived_by')->unsigned()->nullable()->comment('Usuario que archivó el proyecto.');
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
        Schema::dropIfExists('projects');
    }
};
