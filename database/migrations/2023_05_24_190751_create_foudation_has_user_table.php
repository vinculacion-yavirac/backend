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
        Schema::create('foudation_has_user', function (Blueprint $table) {
            $table->id();

            $table->integer('foundations')->unsigned()->nullable();
            $table->foreign('foundations')->references('id')->on('foundations');

            $table->integer('users')->unsigned()->nullable();
            $table->foreign('users')->references('id')->on('users');

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
        Schema::dropIfExists('foudation_has_user');
    }
};
