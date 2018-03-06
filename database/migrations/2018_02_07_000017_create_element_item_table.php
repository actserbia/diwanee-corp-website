<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Constants\ElementType;

class CreateElementItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('element_id');
            $table->unsignedInteger('item_id');
            $table->enum('type', array_keys(ElementType::itemsTypesSettings));
        });

        Schema::table('element_item', function($table) {
            $table->foreign('element_id')->references('id')->on('elements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('element_item');
    }
}
