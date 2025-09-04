<?php

namespace Database\Factories;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'id' => fake()->uuid(),
            'name' => fake()->name(),
            'cpf_cnpj' => fake()->numberBetween(11111111111, 99999999999999),
            'type' => fake()->randomElement(UserType::cases()),
            'email' => fake()->unique()->safeEmail(),
            'balance' => fake()->numberBetween(0, 10000000),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserType::Cusotmer,
            'cpf_cnpj' => fake()->numberBetween(11111111111, 99999999999),
        ]);
    }

    public function merchant(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => UserType::Merchant,
            'cpf_cnpj' => fake()->numberBetween(11111111111111, 99999999999999),
        ]);
    }
}
