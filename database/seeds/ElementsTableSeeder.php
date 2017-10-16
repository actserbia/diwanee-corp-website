<?php

use Illuminate\Database\Seeder;

use App\Element;

class ElementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Element::class, 3)->create();
    }
}
