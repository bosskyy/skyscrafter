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
        // Create default admin user
        User::updateOrCreate([
            'email' => 'admin@percetakan.com',
        ], [
            'name' => 'Admin Percetakan',
            'email' => 'admin@percetakan.com',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
        ]);

        // Create default customer user (for testing)
        User::updateOrCreate([
            'email' => 'customer@example.com',
        ], [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('customer123456'),
            'email_verified_at' => now(),
        ]);

        // Seed products
        $this->call([
            ProductSeeder::class,
        ]);
    }
}
