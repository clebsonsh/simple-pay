<?php

namespace Database\Factories;

use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transfer>
 */
class TransferFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'payer_id' => User::factory()->customer()->create(),
            'payee_id' => User::factory()->create(),
            'value' => fake()->numberBetween(1, 1000000),
        ];
    }
}
