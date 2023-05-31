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
        Schema::create('firma_has_people', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_firmas')->nullable();
            $table->integer('id_people')->unsigned()->nullable();
            $table->string('charge')->nullable();
            $table->foreign(['id_people'], 'firma_has_people_people')->references(['id'])->on('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firma_has_people');
    }
};
