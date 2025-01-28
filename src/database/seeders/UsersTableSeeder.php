<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            ['name' => 'User1', 'email' => 'user1@example.com', 'password' => bcrypt('password')],
            ['name' => 'User2', 'email' => 'user2@example.com', 'password' => bcrypt('password')],
            ['name' => 'User3', 'email' => 'user3@example.com', 'password' => bcrypt('password')],
        ]);
    }
}
