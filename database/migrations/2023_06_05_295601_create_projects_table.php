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
            $table->increments('id');
            $table->string('code',20)->nullable();
            $table->string('name',200)->nullable();
            $table->string('field',100)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->json('linking_activity')->nullable();
            $table->json('sectors_intervention')->nullable();
            $table->json('strategic_axes')->nullable();
            $table->string('term_execution',40)->nullable();
            $table->string('financing',10)->nullable();
            $table->string('coverage',50)->nullable();
            $table->string('modality',30)->nullable();
            $table->string('description',500)->nullable();
            $table->string('situational_analysis',500)->nullable();
            $table->string('foundation',500)->nullable();
            $table->string('justification',500)->nullable();
            $table->json('direct_beneficiaries')->nullable();
            $table->json('indirect_beneficiaries')->nullable();
            $table->string('schedule',200)->nullable();
            $table->json('evaluation_monitoring_strategy')->nullable();
            $table->json('bibliographies')->nullable();
            $table->json('attached_project')->nullable();

            $table->unsignedBigInteger('convention_id')->nullable();
            $table->foreign('convention_id')->references('id')->on('conventions');

            $table->unsignedBigInteger('institute_id')->nullable();
            $table->foreign('institute_id')->references('id')->on('institute');

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
