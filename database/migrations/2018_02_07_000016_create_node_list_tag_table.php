<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeListTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_list_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_list_id');
            $table->unsignedInteger('tag_id');
            $table->unsignedTinyInteger('ordinal_number');
        });

        Schema::table('node_list_tag', function($table) {
            $table->foreign('node_list_id')->references('id')->on('node_lists');
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
        Schema::dropIfExists('node_list_tag');
    }
}
