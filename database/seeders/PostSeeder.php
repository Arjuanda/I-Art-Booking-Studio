<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('documentations')->insert([
        [
            'user_id'=>'1',
            'caption'=>'Percobaan Pertama',
            'pictures' => json_encode([
                'images/bg1.jpg'
            ]),
            'created_at'=>now()
        ],
        [
            'user_id'=>'1',
            'caption'=>'Percobaan Pertama',
            'pictures' => json_encode([
                'images/bg1.jpg',
                'images/bg2.jpg'
            ]),
            'created_at'=>now()
        ],
        [
            'user_id'=>'1',
            'caption'=>'Percobaan Pertama',
            'pictures' => json_encode([
                'images/bg1.jpg',
                'images/bg2.jpg',
                'images/bg3.jpg'
            ]),
            'created_at'=>now()
        ]
        ]);
    }
}
