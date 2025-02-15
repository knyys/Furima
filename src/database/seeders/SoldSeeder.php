<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sold;
use App\Models\Item;
use App\Models\User;

class SoldSeeder extends Seeder
{
    public function run()
    {
        $items = Item::all();

        // 既存のユーザーをランダムに取得
        $users = User::all();

        // Soldテーブルにダミーデータを挿入
        foreach ($items as $item) {
            $user = $users->random();  // ランダムなユーザー

            Sold::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'sold' => 0, // 最初はすべて未販売 (0)
            ]);
        }
    }
}

