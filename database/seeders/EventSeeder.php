<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
        [
            'user_id'=>'1',
            'slug'=>'workshop-1',
            'title'=>'Workshop Pertama',
            'description'=>'Ini adalah workshop pertama kami',
            'date'=>'2025-11-27',
            'poster'=>'poster1.jpg',
            'created_at'=>now()
        ],
        [
            'user_id'=>'1',
            'slug'=>'workshop-2',
            'title'=>'Workshop Kedua',
            'description'=>'Ini adalah workshop kedua kami',
            'date'=>'2025-11-27',
            'poster'=>'poster2.jpg',
            'created_at'=>now()
        ],
        [
            'user_id'=>'1',
            'slug'=>'workshop-3',
            'title'=>'Workshop Ketiga',
            'description'=>'Ini adalah workshop ketiga kami',
            'date'=>'2025-11-27',
            'poster'=>'poster3.jpg',
            'created_at'=>now()
        ]
        ]);
    }
}
