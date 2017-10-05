<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_element', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_article');
            $table->unsignedInteger('id_element');
            $table->unsignedTinyInteger('ordinal_number');
        });

        Schema::table('article_element', function($table) {
            $table->foreign('id_article')->references('id')->on('articles');
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
        Schema::dropIfExists('article_element');
    }
}
