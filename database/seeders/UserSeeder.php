<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->customer()
            ->count(3)
            ->state(new Sequence(
                ['id' => '83666071-2644-39c4-9815-5e339c32c995', 'balance' => 0],
                ['id' => '32fe8f68-5882-348f-a0d8-ee59d67ffc46', 'balance' => 100],
                ['id' => '327e38fc-300f-3889-a332-f8cd7371760a', 'balance' => 100000],
            ))
            ->create();

        User::factory()
            ->merchant()
            ->count(2)
            ->state(new Sequence(
                ['id' => '6d57f7e6-b0ff-3358-b4b6-65e914c220ae', 'balance' => 0],
                ['id' => '77e4f44b-1954-31a8-ba94-ad836264d75e', 'balance' => 100000],
            ))
            ->create();
    }
}
