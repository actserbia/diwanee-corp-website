<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_element', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_node');
            $table->unsignedInteger('id_element');
            $table->unsignedTinyInteger('ordinal_number');
        });

        Schema::table('node_element', function($table) {
            $table->foreign('id_node')->references('id')->on('nodes');
            $table->foreign('id_element')->references('id')->on('elements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_element');
    }
}
