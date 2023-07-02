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
            $table->string('ruc',15)->nullable();
            $table->string('name',100)->nullable();
            $table->string('logo',20)->nullable();
            $table->boolean('state')->nullable();
            $table->string('place_location',200)->nullable();
            $table->string('postal_code',20)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',30)->nullable();
            $table->string('management_nature',25)->nullable();
            $table->string('economic_activity',25)->nullable();

            $table->unsignedBigInteger('addresses_id')->nullable();
            $table->foreign('addresses_id')->references('id')->on('addresses');

            $table->integer('parish_main_id')->nullable();
            $table->foreign('parish_main_id')->references('id')->on('parish');

            $table->integer('parish_branch_id')->nullable();
            $table->foreign('parish_branch_id')->references('id')->on('parish');

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
        Schema::dropIfExists('beneficiary_institutions');
    }
};
