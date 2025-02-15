<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['category' => '腕時計'], //1
            ['category' => '家電'], //2
            ['category' => '食品'], //3
            ['category' => '靴'], //4
            ['category' => '鞄'], //5
            ['category' => 'キッチン用品'], //6
            ['category' => 'コスメ'], //7
            ['category' => 'メンズ'], //8
            ['category' => 'レディース'], //9
        ]);
    }
}
