<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Pro', 'Smart', 'Eco'])
                . ' '
                . fake()->unique()->word
                . ' '
                . fake()->randomElement(['X200', 'Lite', '4K']),
            'price' => fake()->randomFloat(2, 10, 250),
        ];
    }
}
