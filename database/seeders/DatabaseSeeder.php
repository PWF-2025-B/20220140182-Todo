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
        // Membuat satu user khusus
        $users = User::firstOrCreate([
            'id' => 1,
        ],[
            'name' => 'Muhammad Azmi Anshari',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
                //'is_admin' => true
        ]);

        
        User::factory(101)->create();

        $defaultTitles = ['Category 1', 'Category 2', 'Category 3'];
        foreach (\App\Models\User::all() as $user) {
            foreach ($defaultTitles as $title) {
                \App\Models\Category::create([
                    'title' => $title,
                    'user_id' => $user->id,
                ]);
            }
        }

        Todo::factory(500)->create([
            'user_id' => $users -> id
        ]);
    }
}