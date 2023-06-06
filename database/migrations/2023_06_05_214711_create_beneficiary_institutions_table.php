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
        Schema::create('beneficiary_institutions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ruc',15);
            $table->string('name',100);
            $table->string('logo',20);
            $table->boolean('state');
            //$table->string('parish',200);
            $table->string('place_location',200);
            $table->string('postal_code',20);
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
        Schema::dropIfExists('beneficiary_institutions');
    }
};
