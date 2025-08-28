<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'id' => fake()->uuid(),
            'payer_id' => User::factory()->customer()->create(),
            'payee_id' => User::factory()->create(),
            'value' => fake()->numberBetween(1, 1000000),
        ];
    }
}
