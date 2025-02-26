<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $categories = [
        '腕時計',
        '家電',
        '食品',
        '靴',
        '鞄',
        'キッチン用品',
        'コスメ',
        'メンズ',
        'レディース',
        'ファッション',
        'インテリア',
        '本',
        'ゲーム',
        'スポーツ',
        'キッチン',
        'ハンドメイド',
        'アクセサリー',
        'おもちゃ',
        'ベビー・キッズ',
    ];


    foreach ($categories as $category) {
        Category::firstOrCreate(['category' => $category]);
    }
    }
}
