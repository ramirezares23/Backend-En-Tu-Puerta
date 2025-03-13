<?php

namespace Database\Factories;

use Carbon\Carbon;
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
            'date' => Carbon::now()->addUTCDays(2),
            'status' => fake()->randomElement(["Enviada", "Aceptada", "Sin respuesta"]), //TODO: Corregir
            'time'=> fake()->time(),
            'message'=> fake()->text(),
            'id_service' => Service::factory(),
        ];
    }

    //TODO: Visto bueno con equipo
}
