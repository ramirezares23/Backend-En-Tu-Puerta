<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_provider' => User::factory(),
            'service_name' => 'Servicio de ' . fake()->randomElement(["Peluqueria","Manicura","Pedicura","Estilista general"]),
            'service_price' => fake()->randomFloat(2, 0),
            'description' => fake()->text(),
            'images_path' => json_encode(fake()->url()),
            'duration' => fake()->numberBetween(10,300),
        ];
    }
}
