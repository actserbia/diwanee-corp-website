<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementSubelementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element_subelement', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_element');
            $table->unsignedInteger('id_subelement');
        });

        Schema::table('element_subelement', function($table) {
            $table->foreign('id_element')->references('id')->on('elements');
            $table->foreign('id_subelement')->references('id')->on('elements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('element_subelement');
    }
}
