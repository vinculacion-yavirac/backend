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
        Schema::create('foundations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('estatus');
            $table->string('authorized_person');
            $table->string('number_ruc');
            $table->string('economic_activity');
            $table->string('company_email');
            $table->string('company_number');
            $table->string('received_students');
            $table->string('direct_benefit');
            $table->string('indirect_benefits');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('foundations');
    }
};
