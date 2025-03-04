<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'image_url' => 'd66oGvr35Nuvy98XmLspT6WEEZCUmxim14LTdn1t.jpg',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12312312'),
            'phone_number' => '081234567890',
            'gender' => 'male',
            'birth_date' => '1990-01-01',
            'address' => 'Admin Office',
            'role' => 'admin',
        ]);
    }
}
