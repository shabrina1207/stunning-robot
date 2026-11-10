<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUsersSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kasir@coffeeshop.com'],
            [
                'name' => 'Kasir Coffee Shop',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@coffeeshop.com'],
            [
                'name' => 'Admin Coffee Shop',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
