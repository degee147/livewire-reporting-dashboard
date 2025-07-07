<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $categories = ['Food', 'Travel', 'Bills', 'Shopping', 'Entertainment', 'Health', 'Salary', 'Freelance'];

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(), // fallback to factory
            'amount' => $this->faker->randomFloat(2, 5, 500),
            // 'category' => $this->faker->randomElement($categories),
            'category_id' => Category::inRandomOrder()->first()->id,
            'date' => $this->faker->dateTimeBetween('-12 months', 'now')->format('Y-m-d'),
            'description' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['pending', 'completed']),
        ];
    }
}
