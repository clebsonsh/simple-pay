<?php

namespace App\Http\Requests\Api\V1;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferPostRequest extends FormRequest
{
    /** @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'integer',
                'min:1',
                'max:10000000',
            ],
            'payer' => [
                'required',
                /** @todo move this business logic to service */
                // Don't allow merchants to send transfers
                Rule::exists('users', 'id')
                    ->where(fn (Builder $q) => $q->whereNot('type', 'merchant')),
                // User balance need to bigger than transfer value
                Rule::prohibitedIf(function () {
                    $payer = User::query()->where('id', $this->payer)->sole();

                    return $payer->balance < $this->value;
                }),
            ],
            'payee' => [
                'required',
                'exists:users,id',
            ],
        ];

    }

    /**
     * @return array<array<string>>
     */
    public function messages(): array
    {
        return [
            'payer' => [
                'exists' => 'The payer can not be a user type merchant',
                'prohibited' => 'The payer does not have enough balance to send this transfer',
            ],
        ];
    }
}
