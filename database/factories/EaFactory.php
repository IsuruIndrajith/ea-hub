<?php

namespace Database\Factories;

use App\Models\Ea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ea>
 */
class EaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'version' => '1.0',
            'vendor_url' => fake()->url(),
            'primary_keywords' => ['forex robot', 'expert advisor'],
            'secondary_keywords' => [],
            'platform' => 'MT5',
            'product_type' => 'Expert Advisor',
        ];
    }
}
