<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Constants\NodeStatus;

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
            $table->unsignedInteger('node_type_id');
            $table->unsignedInteger('author_id');
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('nodes', function($table) {
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('node_type_id')->references('id')->on('node_types');
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
