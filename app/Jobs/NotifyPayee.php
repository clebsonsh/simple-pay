<?php

namespace App\Jobs;

use App\Repositories\Api\V1\UserRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class NotifyPayee implements ShouldQueue
{
    use Queueable;

    /** @var int */
    public $tries = 5;

    /** @var int */
    public $backoff = 30;

    public function __construct(private readonly string $payee_id) {}

    public function handle(UserRepository $userRepository): void
    {
        $payee = $userRepository->getById($this->payee_id);

        /** @var string $url */
        $url = config('services.notification_service.url');

        $response = Http::retry([100, 200, 500])
            ->baseUrl($url)
            // send user info just to pretend is a real service
            ->post('notify', [
                'userName' => $payee->name,
                'userEmail' => $payee->email,
            ]);

        if (! $response->successful()) {
            $this->fail();
        }
    }
}
