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

        $verbs = ['Corte', 'Poda', 'Limpieza', 'Diseño', 'Reparación', 'Instalación', 'Maquillaje', 'Alisado', 'Coloración'];
        $nouns = ['Cabello', 'Árboles', 'Uñas', 'Pestañas', 'Cejas', 'Jardín', 'Oficina', 'Alfombra', 'Piel'];
        $adjectives = ['Express', 'Profesional', 'Semipermanente', 'Intensivo', 'Completo', 'Básico', 'Premium'];

        // Generar un nombre de servicio aleatorio
        $serviceName = fake()->randomElement($verbs) . ' de ' . fake()->randomElement($nouns);
        if (fake()->boolean(50)) { // 50% de probabilidad de agregar un adjetivo
            $serviceName .= ' ' . fake()->randomElement($adjectives);
        }

        return [
            'id_provider' => User::factory(),
            'service_name' => $serviceName,
            'service_price' => fake()->randomFloat(2, 1),
            'description' => fake()->text(),
            'images_path' => json_encode(fake()->url()),
            'duration' => fake()->numberBetween(10,300),
        ];
    }
}
