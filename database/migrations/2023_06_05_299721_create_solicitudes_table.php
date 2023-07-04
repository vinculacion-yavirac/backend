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

            $table->timestamp('approval_date');

            // $table->unsignedInteger('solicitudes_status_id')->nullable();
            // $table->foreign('solicitudes_status_id')->references('id')->on('catalogs');

            
            $table->unsignedBigInteger('solicitudes_status_id')->nullable();
            $table->foreign('solicitudes_status_id')->references('id')->on('catalogs');

            // $table->unsignedInteger('type_request_id')->nullable();
            // $table->foreign('type_request_id')->references('id')->on('catalogs');

            $table->unsignedBigInteger('type_request_id')->nullable();
            $table->foreign('type_request_id')->references('id')->on('catalogs');

            // $table->unsignedInteger('project_id')->nullable();
            // $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedBigInteger('project_id')->nullable();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false);
            $table->timestamp('archived_at')->nullable();

            $table->unsignedInteger('archived_by')->nullable();
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
