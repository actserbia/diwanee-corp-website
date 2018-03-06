<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeListsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->unsignedInteger('node_type_id');
            $table->unsignedInteger('order_by_field_id')->nullable();
            $table->boolean('order')->nullable();
            $table->unsignedTinyInteger('limit')->default(0);
            $table->unsignedInteger('author_id');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('node_type_id')->references('id')->on('node_types');
            $table->foreign('order_by_field_id')->references('id')->on('fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('node_lists');
    }
}
