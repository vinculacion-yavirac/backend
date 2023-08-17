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
            $table->bigIncrements('id')->comment('ID único de la institución beneficiaria.');

            $table->string('ruc', 15)->comment('RUC de la institución beneficiaria.');

            $table->string('name', 100)->comment('Nombre de la institución beneficiaria.');

            $table->string('name_gestion', 200)->comment('Nombre de la gestión de la institución beneficiaria.');

            $table->string('name_autorize_by', 200)->comment('Nombre de la entidad autorizadora de la institución beneficiaria.');

            $table->string('activity_ruc', 200)->comment('Actividad económica relacionada al RUC de la institución beneficiaria.');

            $table->string('email', 200)->comment('Correo electrónico de contacto de la institución beneficiaria.');

            $table->string('phone', 100)->comment('Número de teléfono de contacto de la institución beneficiaria.');

            $table->string('address', 200)->comment('Dirección física de la institución beneficiaria.');

            $table->string('number_students_start', 3)->comment('Número de estudiantes al inicio.');

            $table->string('number_students_ability', 3)->comment('Número de estudiantes con capacidad.');

            $table->string('Direct beneficiaries', 100)->comment('Beneficiarios directos de la institución.');

            $table->string('Indirect beneficiaries', 100)->comment('Beneficiarios indirectos de la institución.');

            $table->string('logo', 20)->comment('Nombre del archivo del logotipo.');

            $table->boolean('state')->comment('Estado de la institución beneficiaria.');

            $table->string('place_location', 200)->comment('Ubicación geográfica de la institución.');

            $table->string('postal_code', 20)->comment('Código postal de la institución beneficiaria.');

            $table->unsignedBigInteger('parish_id')->nullable()->comment('ID de la parroquia asociada.');

            $table->integer('created_by')->unsigned()->nullable()->comment('Usuario que creó la institución.');

            $table->boolean('archived')->default(false)->comment('Indica si la institución está archivada.');

            $table->timestamp('archived_at')->nullable()->comment('Marca de tiempo cuando se archivó la institución.');

            $table->integer('archived_by')->unsigned()->nullable()->comment('Usuario que archivó la institución.');

            $table->timestamps();

            // Claves foráneas
            if (config('database.default') === 'pgsql') {
                $table->foreign('parish_id')->references('id')->on('addresses')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
            } else {
                $table->foreign('parish_id')->references('id')->on('addresses')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('archived_by')->references('id')->on('users')->onDelete('set null');
            }
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
