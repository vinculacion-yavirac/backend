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
        Schema::create('sub_lines_investigations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 100);
            $table->unsignedBigInteger('research_line_id')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            if (config('database.default') === 'pgsql') {
                $table->foreign('research_line_id')->references('id')->on('research_lines')->onDelete('set null');
            } else {
                $table->foreign('research_line_id')->references('id')->on('research_lines')->onDelete('set null');
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
        Schema::dropIfExists('sub_lines_investigations');
    }
};
