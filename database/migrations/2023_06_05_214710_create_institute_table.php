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
        Schema::create('institute', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number_resolution',20)->nullable();
            $table->string('name',100)->nullable();
            $table->string('logo',20)->nullable();
            $table->boolean('state')->nullable();
            $table->string('city',20)->nullable();
            $table->string('place_location',100)->nullable();
            $table->string('email',30)->nullable();
            $table->string('phone',20)->nullable();
            $table->integer('parish_id')->nullable();
            $table->foreign('parish_id')->references('id')->on('parish');
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
        Schema::dropIfExists('institute');
    }
};
