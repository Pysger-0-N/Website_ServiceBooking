<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\UserModel::create([
            'name' => 'Admin',
            'email' => 'admin@booking.com',
            'role' => 'admin',
            'password' => 'password',
        ]);
        \App\Models\UserModel::create([
            'name' => 'Budi Santoso',
            'email' => 'customer@booking.com',
            'role' => 'customer',
            'password' => 'password',
        ]);
        \App\Models\UserModel::create([
            'name' => 'Joko Provider',
            'email' => 'provider@booking.com',
            'role' => 'provider',
            'password' => 'password',
        ]);
    }
}
