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
            $table->string('name');
            $table->string('description', 300);
            $table->boolean('status')->default(false);

            $table->integer('briefcases')->unsigned()->nullable();
            $table->foreign('briefcases')->references('id')->on('briefcases');

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->boolean('archived')->default(false);
            $table->timestamp('archived_at')->nullable();

            $table->integer('archived_by')->unsigned()->nullable();
            $table->foreign('archived_by')->references('id')->on('users');

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
