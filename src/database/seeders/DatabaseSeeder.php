<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ConditionsTableSeeder::class,
            CategoriesTableSeeder::class,
            UsersTableSeeder::class,
            ItemsTableSeeder::class,
            ProfilesTableSeeder::class,
        ]);
    }
}
