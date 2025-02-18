<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Service;

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
            'id_user' => User::factory(),
            'amount_cents' => fake()->randomFloat(2, 0, 5000),
            'description' => fake()->sentence(),
            'address' => fake()->address(),
            'type' => fake()->randomElement(["Belleza"]),
            'area' => fake()->randomElement(["Peluqueria", "Manicura", "Pedicura", "Estilista general"]),
            'date' =>  //TODO: Tengo este error, quiero asignar la fecha para mañana
            'status' => fake()->randomElement(["Enviada", "Aceptada", "Sin respuesta"]), //TODO: Corregir
            'id_service' => Service::factory(),
        ];
    }

    //TODO: Visto bueno con equipo
}
