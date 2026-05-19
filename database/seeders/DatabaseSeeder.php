<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat Akun Admin
        User::create([
            'name'     => 'Administrator LuxeStep',
            'email'    => 'admin@luxestep.com',
            'password' => Hash::make('password123'), // Password minimal 8 karakter
            'role'     => 'admin',
        ]);

        // Membuat Akun User
        User::create([
            'name'     => 'Novelita Difani',
            'email'    => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role'     => 'user',
        ]);
    }
}