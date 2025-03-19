<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = fake()->time('H:i:s', '18:00:00');

        // Convertir start_time a un objeto Carbon para manipularlo fácilmente
        $startTimeCarbon = Carbon::createFromTimeString($startTime);

        // Sumar entre 5 y 10 horas a start_time para obtener end_time
        $endTimeCarbon = $startTimeCarbon->copy()->addHours(rand(5, 10));

        // Asegurarse de que end_time no sea mayor que 23:00:00 (11:00 PM)
        if ($endTimeCarbon->gt(Carbon::createFromTimeString('23:00:00'))) {
            $endTimeCarbon = Carbon::createFromTimeString('23:00:00');
        }

        // Convertir a formato de tiempo
        $endTime = $endTimeCarbon->format('H:i:s');
        return [
            'code' => fake()->numberBetween(25000000, 31000000), //TODO: corregir este

            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),

            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),

            'phone_number' => fake()->randomElement([
                "0412", "0414", "0416", "0424", "0426"
            ]) . fake()->numberBetween(9999999, 1000000),

            'identity_document' => fake()->numberBetween(25000000, 31000000),

            // 'password' => fake()->password(),
            'password' => static::$password ??= Hash::make('password'),

            'address' => fake()->address(),
            'terms_and_conditions_accept' => fake()->boolean(),
            'start_time' => $startTime, // Usar start_time generado
            'end_time' => $endTime, // Usar end_time calculado

            'type' => fake()->randomElement(["Peluqueria", "Manicura", "Pedicura", "Estilista general"]),

            'profile_image_path' => json_encode(fake()->url()),
            'punctuation' => fake()->numberBetween(0, 5),
            'is_verified' => fake()->boolean(),

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}