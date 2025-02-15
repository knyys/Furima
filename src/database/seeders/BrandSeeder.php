<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
           ['brand' => 'COACH', 'item_id' => 1],
           ['brand' => 'REGAL', 'item_id' => 4],
           ['brand' => 'Lenovo', 'item_id' => 5],
           ['brand' => 'THERMOS', 'item_id' => 8],
           ['brand' => 'HARIO ', 'item_id' => 9],
           ['brand' => 'ETUDE', 'item_id' => 10,],
        ]);
    }
}
