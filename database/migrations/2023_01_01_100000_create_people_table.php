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
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identification_type');
            $table->string('identification');
            $table->string('names');
            $table->string('last_names');
            $table->string('gender')->nullable();
            $table->string('date_birth')->nullable();
            $table->string('place_birth')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('landline_phone')->nullable();

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
        Schema::dropIfExists('people');
    }
};
