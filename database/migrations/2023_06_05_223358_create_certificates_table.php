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
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',20);
            $table->string('certificate_url',200);
            $table->unsignedBigInteger('certificate_type_id')->nullable();
            $table->foreign('certificate_type_id')->references('id')->on('catalogs');
            $table->unsignedBigInteger('certificate_status_id')->nullable();
            $table->foreign('certificate_status_id')->references('id')->on('catalogs');
            $table->unsignedBigInteger('project_participants_id')->nullable();
            $table->foreign('project_participants_id')->references('id')->on('catalogs');
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
        Schema::dropIfExists('certificates');
    }
};
