<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Constants\NodeTypeStatus;

class CreateNodeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->enum('status', NodeTypeStatus::getAll());

            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('node_types');
    }
}
