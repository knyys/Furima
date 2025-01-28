<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            ['name' => '腕時計', 'price' => 15000, 
            'image' => 'storage/public/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
        'user_id' => 1],

            ['name' => 'HDD', 'price' => 5000, 
            'image' => 'storage/public/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
            'user_id' => 2],

            ['name' => '玉ねぎ3束', 'price' => 300, 
            'image' => 'storage/public/iLoveIMG+d.jpg',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
            'user_id' => 3],

            ['name' => '革靴', 'price' => 4000, 
            'image' => 'storage/public/Leather+Shoes+Product+Photo.jpg',
            'detail' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
            'user_id' => 1],

            ['name' => 'ノートPC', 'price' => 45000, 
            'image' => 'storage/public/Living+Room+Laptop.jpg',
            'detail' => '高性能なノートパソコン',
            'condition_id' => 1,
            'user_id' => 2],

            ['name' => 'マイク', 'price' => 8000, 
            'image' => 'storage/public/Music+Mic+4632231.jpg',
            'detail' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
            'user_id' => 2],

            ['name' => 'ショルダーバッグ',
            'price' => 3500, 
            'image' => 'storage/public/Purse+fashion+pocket.jpg',
            'detail' =>  'おしゃれなショルダーバッグ',
            'condition_id' => 3,
            'user_id' => 1],

            ['name' => 'タンブラー',
            'price' => 500, 
            'image' => 'storage/public/Tumbler+souvenir.jpg',
            'detail' =>  '使いやすいタンブラー',
            'condition_id' => 4,
            'user_id' => 3],
            
            ['name' => 'コーヒーミル',
            'price' => 4000, 
            'image' => 'storage/public/Waitress+with+Coffee+Grinder.jpg',
            'detail' =>  '手動のコーヒーミル',
            'condition_id' => 1,
            'user_id' => 3],

            ['name' => 'メイクセット',
            'price' => 2500, 
            'image' => 'storage/public/外出メイクアップセット.jpg',
            'detail' =>  '便利なメイクアップセット',
            'condition_id' => 2,
            'user_id' => 3],
        ]);      

    }
}
