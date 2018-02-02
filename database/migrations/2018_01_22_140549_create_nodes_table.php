<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->enum('status', NodeStatus::getAll());
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('author_id');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('nodes', function($table) {
            $table->foreign('author_id')->references('id')->on('users');
        });

        Schema::table('nodes', function($table) {
            $table->foreign('type_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nodes');
    }
}
