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
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity_name', 200);
            $table->unsignedBigInteger('goals_id')->nullable();

            if (env('DB_CONNECTION') === 'mysql') {
                $table->foreign('goals_id')->references('id')->on('goals')->onDelete('cascade');
            } elseif (env('DB_CONNECTION') === 'pgsql') {
                $table->foreign('goals_id')->references('id')->on('goals')->onDelete('cascade')->onUpdate('cascade');
            }

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
        Schema::dropIfExists('activities');
    }
};
