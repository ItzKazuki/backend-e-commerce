<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Kazuki',
            'email' => 'test@account.com',
            'phone' => '62859102628529',
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);

        User::factory()->create([
            'name' => 'Ucup',
            'email' => 'ucup@account.com',
            'phone' => '6258954384758',
            'role' => 'seller',
            'password' => Hash::make('password')
        ]);

        User::factory()->create([
            'name' => 'Yahya',
            'email' => 'aya@account.com',
            'phone' => '62899966435536',
            'role' => 'seller',
            'password' => Hash::make('password')
        ]);

        User::factory()->create([
            'name' => 'Chaeza Ibnu',
            'email' => 'chaezaibnuakbar@gmail.com',
            'phone' => '62859102628529',
            'role' => 'seller',
            'password' => Hash::make('password')
        ]);

        User::factory(10)->create();
    }
}
