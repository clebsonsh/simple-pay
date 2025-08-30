<?php

namespace App\Models;

use Database\Factories\TransferFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    /** @use HasFactory<TransferFactory> */
    use HasFactory, HasUuids;

    const UPDATED_AT = null;

    /** @var list<string> */
    protected $fillable = [
        'payer_id',
        'payee_id',
        'value',
    ];

    /** @return BelongsTo<User, $this> */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id', 'id');
    }

    /** @return BelongsTo<User, $this> */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id', 'id');
    }
}
