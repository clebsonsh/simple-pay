<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->customer()->count(5)->create();

        User::factory()->merchant()->count(5)->create();
    }
}
