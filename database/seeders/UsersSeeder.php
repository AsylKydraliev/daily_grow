<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем супер-админа
        User::factory()->create([
            'name' => 'Супер Админ',
            'login' => 'superadmin',
            'role' => 'супер-админ',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Создаем админа
        User::factory()->create([
            'name' => 'Админ',
            'login' => 'admin',
            'role' => 'админ',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Создаем несколько продавцов
        User::factory()->count(3)->create([
            'role' => 'продавец',
        ]);

        // Создаем еще несколько админов
        User::factory()->count(2)->create([
            'role' => 'админ',
        ]);

        // Создаем остальных пользователей (продавцы)
        User::factory()->count(13)->create([
            'role' => 'продавец',
        ]);
    }
}
