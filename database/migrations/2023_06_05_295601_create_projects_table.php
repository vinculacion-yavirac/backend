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
            $table->bigIncrements('id');
            $table->string('code', 20);
            $table->string('name', 200);
            $table->string('name_institute', 200);
            $table->string('cicle', 50);
            $table->string('address', 200);
            $table->string('Modality', 50);
            $table->string('field', 100);
            $table->integer('term_execution');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->json('linking_activity');
            $table->json('sectors_intervention');
            $table->json('strategic_axes');
            $table->string('description', 500);
            $table->string('situational_analysis', 500);
            $table->string('foundation', 500);
            $table->string('justification', 500);
            $table->json('direct_beneficiaries');
            $table->json('indirect_beneficiaries');
            $table->string('schedule', 200);
            $table->json('evaluation_monitoring_strategy');
            $table->json('bibliographies');
            $table->json('attached_project');

            $table->unsignedBigInteger('convention_id')->nullable();
            $table->foreign('convention_id')->references('id')->on('conventions');

            $table->unsignedBigInteger('school_period_id')->nullable();
            $table->foreign('school_period_id')->references('id')->on('school_periods');

            $table->unsignedBigInteger('beneficiary_institution_id')->nullable();
            $table->foreign('beneficiary_institution_id')->references('id')->on('beneficiary_institutions');

            $table->unsignedBigInteger('career_id')->nullable();
            $table->foreign('career_id')->references('id')->on('careers');

            $table->unsignedBigInteger('sub_line_investigation_id')->nullable();
            $table->foreign('sub_line_investigation_id')->references('id')->on('sub_lines_investigations');

            $table->unsignedBigInteger('authorized_by')->nullable();
            $table->foreign('authorized_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('made_by')->nullable();
            $table->foreign('made_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('responsibles');

            $table->unsignedBigInteger('catalogue_id')->nullable();
            $table->foreign('catalogue_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('stateTwo_id')->nullable();
            $table->foreign('stateTwo_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('frequency_id')->nullable();
            $table->foreign('frequency_id')->references('id')->on('catalogs');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false);
            $table->timestamp('archived_at')->nullable();

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
        Schema::dropIfExists('projects');
    }
};
