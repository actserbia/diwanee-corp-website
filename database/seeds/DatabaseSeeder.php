<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(FieldTypesTableSeeder::class);
        $this->call(NodeTypesTableSeeder::class);
        $this->call(FieldTableSeeder::class);

        Artisan::call('nmtype:generate');
    }
}
