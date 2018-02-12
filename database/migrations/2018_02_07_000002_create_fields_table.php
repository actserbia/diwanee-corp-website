<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->unsignedInteger('field_type_id');
            
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::table('fields', function($table) {
            $table->foreign('field_type_id')->references('id')->on('field_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
