<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Constants\NodeListRelationType;

class CreateNodeListRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_list_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_list_id');
            $table->unsignedInteger('relation_id');
            $table->enum('type', NodeListRelationType::getAll());
        });

        Schema::table('node_list_relation', function($table) {
            $table->foreign('node_list_id')->references('id')->on('node_lists');
            //$table->foreign('relation_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_list_relation');
    }
}
