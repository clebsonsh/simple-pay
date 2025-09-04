<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

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
            ],
            'payee' => [
                'required',
                'exists:users,id',
            ],
        ];

    }
}
