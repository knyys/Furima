<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        // image保存先
        $relativePath = 'profile/images.png';
        $sourcePath = storage_path('app/public/profile/images.png');

        if (!Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->put($relativePath, file_get_contents($sourcePath));
        }
        $imageUrl = asset('storage/' . $relativePath);

        // プロフィールデータ
        $profiles = [
            [
                'name' => 'User1',
                'address_number' => '111-1111',
                'address' => '東京都新宿区新宿1-1-1',
                'building' => '新宿タワー101',
            ],
            [
                'name' => 'User2',
                'address_number' => '222-2222',
                'address' => '東京都新宿区新宿2-2-2',
                'building' => '新宿タワー202',
            ],
            [
                'name' => 'User3',
                'address_number' => '333-3333',
                'address' => '東京都新宿区新宿3-3-3',
                'building' => '新宿タワー303',
            ],
        ];

        foreach ($profiles as $data) {
            $user = User::where('name', $data['name'])->first();

            if (!$user) {
                $this->command->warn("{$data['name']} が見つかりませんでした。");
                continue;
            }

            DB::table('profiles')->insert([
                'user_id' => $user->id,
                'address_number' => $data['address_number'],
                'address' => $data['address'],
                'building' => $data['building'],
                'image' => $imageUrl,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}
