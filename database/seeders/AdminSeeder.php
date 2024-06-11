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
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
        ]);

        Admin::create([
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin3@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin4@example.com',
            'password' => Hash::make('password'),
        ]);

        Admin::create([
            'email' => 'admin5@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
