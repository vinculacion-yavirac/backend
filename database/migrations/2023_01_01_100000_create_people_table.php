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
        $table->increments('id')->comment('ID único de la persona.');

        $table->string('identification_type')->comment('Tipo de identificación de la persona (por ejemplo, pasaporte, cédula, etc.).');

        $table->string('identification')->comment('Número de identificación de la persona.');

        $table->string('names')->comment('Nombres de la persona.');

        $table->string('last_names')->comment('Apellidos de la persona.');

        $table->string('gender')->nullable()->comment('Género de la persona.');

        $table->string('date_birth')->nullable()->comment('Fecha de nacimiento de la persona.');

        $table->string('place_birth')->nullable()->comment('Lugar de nacimiento de la persona.');

        $table->string('mobile_phone')->nullable()->comment('Número de teléfono móvil de la persona.');

        $table->string('landline_phone')->nullable()->comment('Número de teléfono fijo de la persona.');

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
