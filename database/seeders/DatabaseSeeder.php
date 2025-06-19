<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'role' => 'SuperAdmin',
                'status' => 1,
                'phone' => '08666',
                'password' => bcrypt('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Yeonjun',
                'email' => 'txt@gmail.com',
                'role' => 'Admin',
                'status' => 1,
                'phone' => '08555',
                'password' => bcrypt('tamvan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Karla',
                'email' => 'karla@gmail.com',
                'role' => 'Customer',
                'status' => 1,
                'phone' => '08595',
                'password' => bcrypt('menawan'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        Category::factory(4)->create();
    }
}
