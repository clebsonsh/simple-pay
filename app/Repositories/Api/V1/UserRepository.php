<?php

namespace App\Repositories\Api\V1;

use App\Models\User;

class UserRepository
{
    public function getById(string $id): User
    {
        return User::query()->findOrFail($id);
    }

    public function decrementBalanceById(string $id, int $value): void
    {
        $user = $this->getById($id);

        $user->update(['balance' => $user->balance - $value]);
    }

    public function incrementBalanceById(string $id, int $value): void
    {
        $user = $this->getById($id);

        $user->update(['balance' => $user->balance + $value]);
    }
}
