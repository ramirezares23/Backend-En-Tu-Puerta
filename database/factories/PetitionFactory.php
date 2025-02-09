<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Petition>
 */
class PetitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => fake()->number(),
            'amount_cents' => fake()->randomFloat(2, 0, 5000),
            'description' => fake()->sentence(),
            'type' => fake()->jobTitle(),
            'area' => fake()->jobTitle(),
            'datetime' => now(),
            'status' => fake()->sentence(1),
        ];
    }

    //TODO: Visto bueno con equipo
}
