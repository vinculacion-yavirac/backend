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
        Schema::create('project_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->json('functions');
            $table->timestamp('assignment_date');

            $table->unsignedBigInteger('level_id')->nullable();
            $table->foreign('level_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('catalogue_id')->nullable();
            $table->foreign('catalogue_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->foreign('schedule_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->integer('participant_id')->unsigned()->nullable();
            $table->foreign('participant_id')->references('id')->on('users');

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
        Schema::dropIfExists('project_participants');
    }
};
