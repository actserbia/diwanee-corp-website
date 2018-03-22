<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_id');
            $table->unsignedInteger('tag_id');
            $table->unsignedTinyInteger('pivot_ordinal_number');
        });

        Schema::table('node_tag', function($table) {
            $table->foreign('node_id')->references('id')->on('nodes');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_tag');
    }
}
