<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\NodeType;
use App\Constants\Database;

class NodeTypesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Schema::table('node_types', function (Blueprint $table) {
            $table->unsignedInteger('id')->change();
        });
        
        factory(NodeType::class, 1)->create([
            'id' => Database::NodeType_Page_Id,
            'name' => 'Page'
        ]);
        
        factory(NodeType::class, 1)->create([
            'id' => Database::NodeType_Queue_Id,
            'name' => 'Queue'
        ]);
        
        factory(NodeType::class, 1)->create([
            'id' => Database::NodeType_TagData_Id,
            'name' => 'Tag Data'
        ]);
        
        Schema::table('node_types', function (Blueprint $table) {
            $table->increments('id')->change();
        });
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
