<?php

namespace App\Services\Api\V1;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AuthorizationService
{
    /** @throws ConnectionException */
    public function check(): bool
    {
        /** @var string $url */
        $url = config('services.authorization_service.url');

        $response = Http::baseUrl($url)->get('authorize');
        $statusCode = $response->status();

        return $statusCode === Response::HTTP_OK;
    }
}
