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
            ['category' => '腕時計', 'item_id' => 1],
            ['category' => '家電', 'item_id' => 2],
            ['category' => '食品', 'item_id' => 3],
            ['category' => '靴', 'item_id' => 4],
            ['category' => '家電', 'item_id' => 5],
            ['category' => '家電', 'item_id' => 6],
            ['category' => '鞄', 'item_id' => 7],
            ['category' => 'キッチン用品', 'item_id' => 8],
            ['category' => 'キッチン用品', 'item_id' => 9],
            ['category' => 'コスメ', 'item_id' => 10,],
            ['category' => 'メンズ', 'item_id' => 1],
         ]);
    }
}
