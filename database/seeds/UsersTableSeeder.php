<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Constants\UserRole;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 1)->create([
            'name' => 'jelena',
            'email' => 'jelena.romcevic@diwanee.com',
            'role' => UserRole::Admin
        ]);

        factory(User::class, 1)->create([
            'name' => 'katarina',
            'email' => 'katarina.caric@diwanee.com',
            'role' => UserRole::Admin
        ]);

        factory(User::class, 1)->create([
            'name' => 'moderator',
            'email' => 'moderator@diwanee.com',
            'role' => UserRole::Moderator
        ]);

        factory(User::class, 1)->create([
            'name' => 'brand',
            'email' => 'brand@diwanee.com',
            'role' => UserRole::Brand
        ]);

        factory(User::class, 1)->create([
            'name' => 'user',
            'email' => 'user@diwanee.com',
            'role' => UserRole::User
        ]);
    }
}
