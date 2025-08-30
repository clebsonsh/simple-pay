<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'cpf_cnpj',
        'type',
        'email',
        'balance',
        'password',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /** @return HasMany<Transfer, $this> */
    public function transfersAsPayer(): HasMany
    {
        return $this->hasMany(Transfer::class, 'payer_id', 'id');
    }

    /** @return HasMany<Transfer, $this> */
    public function transfersAsPayee(): HasMany
    {
        return $this->hasMany(Transfer::class, 'payee_id', 'id');
    }
}
