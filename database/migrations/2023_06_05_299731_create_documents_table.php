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
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->comment('Document Name: Commitment letter, start report, attendance control, attendance record, final report, compliance control, certificate, perception survey, control report.');
            $table->string('template',200)->comment('the document is saved on the server.');
            $table->boolean('state');
            $table->integer('order')->comment('order in how it will be printed on the screen.');
            $table->integer('responsible_id')->unsigned()->nullable()->comment('role responsible for filling out the document.');
            $table->foreign('responsible_id')->references('id')->on('roles');
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
        Schema::dropIfExists('documents');
    }
};
