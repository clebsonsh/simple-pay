<?php

use App\Models\Transfer;
use App\Models\User;

describe('Transfer', function () {
    it('creates a single transfer', function () {
        $transfer = Transfer::factory()->create();

        $this->assertModelExists($transfer);
    });

    it('creates multiple transfers', function () {
        $count = 5;
        Transfer::factory()->count($count)->create();

        $this->assertDatabaseCount('transfers', $count);
    });

    it('creates transfer with a payer and payee', function () {
        $payer = User::factory()->create();
        $payee = User::factory()->create();

        $transfer = Transfer::create([
            'payer_id' => $payer->id,
            'payee_id' => $payee->id,
            'value' => 1000,
        ]);

        expect($payer->is($transfer->payer))->toBeTrue();
        expect($payee->is($transfer->payee))->toBeTrue();
    });
});
