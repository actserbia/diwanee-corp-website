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
            $table->unsignedInteger('node_id');
            $table->unsignedInteger('element_id');
            $table->unsignedTinyInteger('pivot_ordinal_number');

            $table->foreign('node_id')->references('id')->on('nodes');
            $table->foreign('element_id')->references('id')->on('elements');
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
