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
        Schema::create('official_document_has_recipients', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('official_document')->unsigned()->nullable();
            $table->foreign('official_document')->references('id')->on('official_documents');

            $table->integer('users')->unsigned()->nullable();
            $table->foreign('users')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_document_has_recipients');
    }
};
