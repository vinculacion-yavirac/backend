<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsibleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsibles', function (Blueprint $table) {
            $table->bigIncrements('id');
            //$table->unsignedBigInteger('users_id')->nullable();
            //$table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->integer('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users');
            
            $table->unsignedBigInteger('charges_id')->nullable();
            $table->foreign('charges_id')->references('id')->on('catalogs')->onDelete('cascade');
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
        Schema::dropIfExists('responsibles');
        
    }
}
