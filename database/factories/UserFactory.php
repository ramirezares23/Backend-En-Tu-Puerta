<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return [
                'code' => fake()->realTextBetween(2,8), //TODO: corregir este

            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),

            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),

            'phone_number' => fake()->phoneNumber(),

            'identity_document' => fake()->numerify(), //TODO: corregir este

            'password' => fake()->password(),
            //'password' => static::$password ??= Hash::make('password'),

            'address' => fake()->address(),
            'terms_and_conditions_accept' => fake()->boolean(),
            'schedules' => json_encode(fake()->dayOfWeek()),

            'type' => fake()->jobTitle(),
            'area' => fake()->jobTitle(),

            'images_paths' => json_encode(fake()->url()),
            'punctuation' => fake()->numberBetween(0,5),

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
