<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザー「User1」を取得
        $user = User::where('name', 'User1')->first();

        if (!$user) {
            $this->command->error('User1が見つかりませんでした');
            return;
        }

        // 保存先
        $relativePath = 'profile_images/images.png';
        $sourcePath = storage_path('app/public/profile/images.png');

        // まだストレージにないならコピー
        if (!Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->put($relativePath, file_get_contents($sourcePath));
        }

        // ⭐ URL形式で保存する（←ここが変更点！）
        $imageUrl = asset('storage/' . $relativePath);


        DB::table('profiles')->insert([
            'user_id' => $user->id,
            'address_number' => '111-1111',
            'address' => '東京都渋谷区',
            'building' => '渋谷ビル101',
            'image' => $imageUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
    }
}
