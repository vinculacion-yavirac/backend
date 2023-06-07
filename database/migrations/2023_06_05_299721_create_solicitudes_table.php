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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('comment');

            $table->integer('solicitudes_status_id')->unsigned()->nullable();
            $table->foreign('solicitudes_status_id')->references('id')->on('catalogs');

            $table->integer('type_request_id')->unsigned()->nullable();
            $table->foreign('type_request_id')->references('id')->on('catalogs');

            $table->integer('who_made_request_id')->unsigned()->nullable();
            $table->foreign('who_made_request_id')->references('id')->on('project_participants');

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
        Schema::dropIfExists('solicitudes');
    }
};
