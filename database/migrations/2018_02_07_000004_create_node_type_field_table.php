<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeTypeFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_type_field', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('node_type_id');
            $table->unsignedInteger('field_id');
            $table->boolean('active')->default(1);
            $table->boolean('required')->default(0);
            $table->longText('additional_settings')->nullable();
            $table->unsignedTinyInteger('pivot_ordinal_number');

            $table->foreign('node_type_id')->references('id')->on('node_types');
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_type_field');
    }
}
