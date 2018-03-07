<?php

use Illuminate\Database\Seeder;
use \App\NodeType;

class NodeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(NodeType::class, 1)->create([
            'name' => 'Page'
        ]);

        factory(NodeType::class, 1)->create([
            'name' => 'Queue'
        ]);

    }
}
