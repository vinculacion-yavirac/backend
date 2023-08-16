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
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('type', 200);
            $table->longText('content');
            $table->string('observation', 200);
            $table->boolean('state');
            $table->integer('size');
            $table->integer('briefcase_id')->unsigned()->nullable();
            $table->integer('document_id')->unsigned()->nullable();
            $table->timestamps();
    
            $table->foreign('briefcase_id')
                ->references('id')
                ->on('briefcases')
                ->onDelete('cascade'); // Eliminación en cascada
    
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->onDelete('cascade'); // Eliminación en cascada
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
