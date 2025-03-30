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
        $item1 = Item::create([
            'name' => '腕時計',
            'price' => 15000, 
            'image' => 'images/Armani+Mens+Clock.jpg',
            'detail' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition_id' => 1,
            'user_id' => 1,
            'brand' => 'COACH'
        ]);
        $item1->categories()->attach([1, 8]);

        $item2 = Item::create([
            'name' => 'HDD',
            'price' => 5000, 
            'image' => 'images/HDD+Hard+Disk.jpg',
            'detail' => '高速で信頼性の高いハードディスク',
            'condition_id' => 2,
            'user_id' => 2,
            'brand' => null
        ]);
        $item2->categories()->attach([2]);

        $item3 = Item::create([
            'name' => '玉ねぎ3束',
            'price' => 300, 
            'image' => 'images/iLoveIMG+d.jpg',
            'detail' => '新鮮な玉ねぎ3束のセット',
            'condition_id' => 3,
            'user_id' => 3,
            'brand' => null
        ]);
        $item1->categories()->attach([3]);

        $item4 = Item::create([
            'name' => '革靴',
            'price' => 4000, 
            'image' => 'images/Leather+Shoes+Product+Photo.jpg',
            'detail' => 'クラシックなデザインの革靴',
            'condition_id' => 4,
            'user_id' => 1,
            'brand' => 'REGAL'
        ]);
        $item4->categories()->attach([4, 8]);

        $item5 = Item::create([
            'name' => 'ノートPC',
            'price' => 45000, 
            'image' => 'images/Living+Room+Laptop.jpg',
            'detail' => '高性能なノートパソコン',
            'condition_id' => 1,
            'user_id' => 2,
            'brand' => 'Lenovo'
        ]);
        $item5->categories()->attach([2]);

        $item6 = Item::create([
            'name' => 'マイク',
            'price' => 8000, 
            'image' => 'images/Music+Mic+4632231.jpg',
            'detail' => '高音質のレコーディング用マイク',
            'condition_id' => 2,
            'user_id' => 2,
            'brand' => null
        ]);
        $item6->categories()->attach([2]);

        $item7 = Item::create([
            'name' => 'ショルダーバッグ',
            'price' => 3500, 
            'image' => 'images/Purse+fashion+pocket.jpg',
            'detail' =>  'おしゃれなショルダーバッグ',
            'condition_id' => 3,
            'user_id' => 1,
            'brand' => null
        ]);
        $item7->categories()->attach([5, 9]);
        
        $item8 = Item::create([
            'name' => 'タンブラー',
            'price' => 500, 
            'image' => 'images/Tumbler+souvenir.jpg',
            'detail' =>  '使いやすいタンブラー',
            'condition_id' => 4,
            'user_id' => 3,
            'brand' => 'THERMOS'
        ]);
        $item8->categories()->attach([6]);
    
        $item9 = Item::create([
            'name' => 'コーヒーミル',
            'price' => 4000, 
            'image' => 'images/Waitress+with+Coffee+Grinder.jpg',
            'detail' =>  '手動のコーヒーミル',
            'condition_id' => 1,
            'user_id' => 3,
            'brand' =>  'HARIO'
        ]);
        $item9->categories()->attach([6]);

        $item10 = Item::create([
            'name' => 'メイクセット',
            'price' => 2500, 
            'image' => 'images/外出メイクアップセット.jpg',
            'detail' =>  '便利なメイクアップセット',
            'condition_id' => 2,
            'user_id' => 3,
            'brand' => 'CANMAKE'
        ]); 
        $item10->categories()->attach([7, 9]);

    }
}
