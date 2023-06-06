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
            $table->timestamps();
            $table->string('code',20);
            $table->string('name',200);
            //$table->string('beneficiary_institution',200);
            $table->string('field',100);
            $table->integer('term_execution');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->json('linking_activity');
            $table->json('sectors_intervention');
            $table->json('strategic_axes');
            $table->string('description',500);
            $table->string('situational_analysis',500);
            $table->string('foundation',500);
            $table->string('justification',500);
            $table->json('direct_beneficiaries');
            $table->json('indirect_beneficiaries');
            $table->string('schedule',200);
            $table->json('evaluation_monitoring_strategy');
            $table->json('bibliographies');
            $table->json('attached_project');
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
