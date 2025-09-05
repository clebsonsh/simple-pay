<?php

use App\Models\Transfer;
use App\Models\User;

describe('User', function () {
    it('creates a single user', function () {
        $user = User::factory()->create();

        $this->assertModelExists($user);
    });

    it('creates multiple users', function () {
        $count = 5;
        User::factory()->count($count)->create();

        $this->assertDatabaseCount('users', $count);
    });

    it('can have many transfers as payer', function () {
        $count = 5;
        $user = User::factory()->create();

        Transfer::factory()->count($count)->create([
            'payer_id' => $user->id,
        ]);

        expect($user->transfersAsPayer()->count())->toBe($count);
    });

    it('can have many transfers as payee', function () {
        $count = 5;
        $user = User::factory()->create();

        Transfer::factory()->count($count)->create([
            'payee_id' => $user->id,
        ]);

        expect($user->transfersAsPayee()->count())->toBe($count);
    });
});
