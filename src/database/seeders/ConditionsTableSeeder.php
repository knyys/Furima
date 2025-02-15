<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Condition;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conditions')->insert([
            ['condition' => '良好', 'item_id' => 1],
            ['condition' => '目立った傷や汚れなし', 'item_id' => 2],
            ['condition' => 'やや傷や汚れあり', 'item_id' => 3],
            ['condition' => '状態が悪い', 'item_id' => 4], 
            ['condition' => '良好', 'item_id' => 5],
            ['condition' => '目立った傷や汚れなし', 'item_id' => 6],
            ['condition' => 'やや傷や汚れあり', 'item_id' => 7],
            ['condition' => '状態が悪い', 'item_id' => 8],
            ['condition' => '良好', 'item_id' => 9],
            ['condition' => '目立った傷や汚れなし', 'item_id' => 10],
        ]);
    }
}
