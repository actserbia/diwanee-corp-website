<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Constants\FieldTypeCategory;

class CreateFieldTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_types', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name', 255);
            $table->enum('category', FieldTypeCategory::getAll());
            
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
        Schema::dropIfExists('field_types');
    }
}
