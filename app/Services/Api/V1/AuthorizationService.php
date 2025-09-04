<?php

namespace App\Services\Api\V1;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AuthorizationService
{
    private PendingRequest $httpClient;

    public function __construct()
    {
        /** @var string $url */
        $url = config('services.authorization_service.url');

        $this->httpClient = Http::baseUrl($url);
    }

    /** @throws ConnectionException */
    public function check(): bool
    {
        $response = $this->httpClient->get('authorize');
        $statusCode = $response->status();

        return $statusCode === Response::HTTP_OK;
    }
}
