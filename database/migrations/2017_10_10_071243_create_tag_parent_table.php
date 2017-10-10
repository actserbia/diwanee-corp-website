<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagParentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_parent', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_tag');
            $table->unsignedInteger('id_parent');
            $table->unsignedTinyInteger('ordinal_number');
        });

        Schema::table('tag_parent', function($table) {
            $table->foreign('id_tag')->references('id')->on('tags');
            $table->foreign('id_parent')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_parent');
    }
}
