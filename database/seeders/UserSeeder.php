<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
        [
            'name'=>'Bobby',
            'badge'=>'30174618',
            'email'=>'bobby@gmail.com',
            'contact'=>'82216456842',
            'role'=>'Admin',
            'status'=>'active',
            'password' => Hash::make('admin12345'),
            'created_at'=>now(),
            'updated_at' => now()
        ],
        [
            'name'=>'Arjuanda',
            'badge'=>'30174619',
            'email'=>'arjuanda@gmail.com',
            'contact'=>'822135356842',
            'role'=>'Karyawan',
            'status'=>'active',
            'password' => Hash::make('admin12345'),
            'created_at'=>now(),
            'updated_at' => now()
        ],
        ]);
    }
}
