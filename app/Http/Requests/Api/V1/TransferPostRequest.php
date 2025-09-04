<?php

namespace App\Http\Requests\Api\V1;

use App\Models\User;
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
                'exists:users,id',
                // User balance need to bigger than transfer value
                /** @todo create a test for this before move it to a transaction service */
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
