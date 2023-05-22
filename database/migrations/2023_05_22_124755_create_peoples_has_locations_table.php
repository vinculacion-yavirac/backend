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
        Schema::create('peoples_has_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hause_number');
            
            $table->integer('locations')->unsigned()->nullable();
            $table->foreign('locations')->references('id')->on('locations');

            $table->integer('people')->unsigned()->nullable();
            $table->foreign('people')->references('id')->on('people');
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
        Schema::dropIfExists('peoples_has_locations');
    }
};
