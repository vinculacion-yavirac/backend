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
            $table->bigIncrements('id');
            $table->string('ruc', 15);
            $table->string('name', 100);
            $table->string('name_gestion', 200);
            $table->string('name_autorize_by', 200);
            $table->string('activity_ruc', 200);
            $table->string('email', 200);
            $table->string('phone', 100);
            $table->string('address', 200);
            $table->string('number_students_start', 3);
            $table->string('number_students_ability', 3);
            $table->string('Direct beneficiaries', 100);
            $table->string('Indirect beneficiaries', 100);
            $table->string('logo', 20);
            $table->boolean('state');
            $table->string('place_location', 200);
            $table->string('postal_code', 20);
            $table->unsignedBigInteger('parish_id')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamp('archived_at')->nullable();
            $table->integer('archived_by')->unsigned()->nullable();
            $table->timestamps();

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
