<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat 10 user secara otomatis
        // User::factory(10)->create();

        // Membuat admin dengan password yang di-hash
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => true,
        ]);
        User::factory()->create([
            'name' => 'Muhamad Adri Muwaffaq Khamid', // Ganti dengan nama lengkap kamu
            'email' => 'adri.example@mail.com', // Ganti dengan email kamu
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => false,
        ]);     // Membuat 100 todo secara otomati    
    }
}
