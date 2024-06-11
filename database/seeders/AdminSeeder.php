<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        Admin::create([
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
        ]);

        Admin::create([
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin3@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin4@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin5@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
