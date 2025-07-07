<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);


        $categories = ['Food', 'Travel', 'Bills', 'Shopping', 'Entertainment', 'Health', 'Salary', 'Freelance'];

        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }

        $users = User::factory(10)->create();

        // Create 1000+ transactions distributed across users
        foreach ($users as $user) {
            Transaction::factory(100)->create([
                'user_id' => $user->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);
        }

    }
}
