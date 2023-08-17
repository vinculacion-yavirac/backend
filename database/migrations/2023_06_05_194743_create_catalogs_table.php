<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID único del catálogo.');

            $table->string('code', 20)->comment('Código del catálogo.');

            $table->string('catalog_type', 20)->comment('Tipo del catálogo.');

            $table->string('catalog_value', 100)->comment('Valor del catálogo.');

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
        Schema::dropIfExists('catalogs');
    }
}
