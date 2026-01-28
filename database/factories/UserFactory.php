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
            'nombre' => fake()->firstName(),
            'apellidos' => fake()->lastName(),
            'empresa_id' => fake()->numberBetween(1, 5), // Asume que tienes al menos 5 empresas
            'departamento_id' => fake()->numberBetween(1, 5), // Asume que tienes al menos 5 departamentos
            'centro_id' => fake()->numberBetween(1, 5), // Asume que tienes al menos 5 centros
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
            'extension' => fake()->randomNumber(3, true), // Genera una extensión de 3 dígitos
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
