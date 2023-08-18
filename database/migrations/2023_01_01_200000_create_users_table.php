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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('ID único del usuario.');

            $table->string('email')->unique()->comment('Dirección de correo electrónico del usuario.');

            $table->string('password')->comment('Contraseña del usuario.');

            $table->unsignedInteger('person')->unique()->comment('ID de la persona asociada al usuario.');
            $table->foreign('person')
                ->references('id')
                ->on('people')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->comment('Relación con la tabla de personas. Cuando se actualiza o elimina una persona, se refleja en este usuario.');

            $table->boolean('active')->default(true)->comment('Indica si el usuario está activo.');

            $table->boolean('archived')->default(false)->comment('Indica si el usuario está archivado.');

            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó el usuario.');

            $table->unsignedInteger('archived_by')->nullable()->comment('Usuario que archivó el usuario.');
            $table->foreign('archived_by')->references('id')->on('users')->comment('Relación con la tabla de usuarios para registrar quién archivó el usuario.');

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
        Schema::dropIfExists('users');
    }
};
