<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);

        // Membuat 100 user tambahan secara otomatis
        User::factory(100)->create();
        // Membuat 100 todo secara otomatis
        Todo::factory(100)->create();
    }
}
