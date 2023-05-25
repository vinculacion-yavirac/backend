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
        Schema::create('locations_has_foundations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_ubicacion');
            $table->integer('locations')->unsigned()->nullable();
            $table->foreign('locations')->references('id')->on('locations');

            $table->integer('foundations')->unsigned()->nullable();
            $table->foreign('foundations')->references('id')->on('foundations');
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
