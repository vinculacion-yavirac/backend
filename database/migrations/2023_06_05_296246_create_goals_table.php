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
        Schema::create('goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('target_name', 500);
            $table->json('media_verification');
            $table->string('verifiable_indicators');


            $table->unsignedBigInteger('father_goals_id')->nullable();
            $table->foreign('father_goals_id')->references('id')->on('goals');

            $table->unsignedBigInteger('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->unsignedBigInteger('target_type_id')->nullable();
            $table->foreign('target_type_id')->references('id')->on('catalogs');

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
        Schema::dropIfExists('goals');
    }
};
