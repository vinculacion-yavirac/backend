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
        Schema::create('briefcases_has_users', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('briefcases')->unsigned()->nullable();
            $table->foreign('briefcases')->references('id')->on('briefcases');

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
        Schema::dropIfExists('briefcases_has_users');
    }
};
