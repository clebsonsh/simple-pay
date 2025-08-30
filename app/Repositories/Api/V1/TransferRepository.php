<?php

namespace App\Repositories\Api\V1;

use App\Models\Transfer;

class TransferRepository
{
    public function create(string $payer_id, string $payee_id, int $value): Transfer
    {
        return Transfer::query()->create([
            'payer_id' => $payer_id,
            'payee_id' => $payee_id,
            'value' => $value,
        ]);
    }
}
