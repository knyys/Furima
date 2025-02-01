<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\Item;
use App\Models\User;

class LikeSeeder extends Seeder
{
    public function run()
    {
        $items = Item::all();

        // 既存のユーザーをランダムに取得
        $users = User::all();

        foreach ($items as $item) {
            $user = $users->random();  // ランダムなユーザー

            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'like' => 0, // 最初はすべて (0)
            ]);
        }
    }
}

