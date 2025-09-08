<?php

namespace App\Jobs;

use App\Exceptions\NotificationServiceUnavailableException;
use App\Models\Transfer;
use App\Models\User;
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

    public function __construct(private readonly Transfer $transfer) {}

    public function handle(): void
    {
        /** @var User $payee */
        $payee = $this->transfer->payee;

        /** @var string $url */
        $url = config('services.notification_service.url');

        $response = Http::retry([250, 500], throw: false)
            ->baseUrl($url)
            // send user info just to pretend is a real service
            ->post('notify', [
                'userName' => $payee->name,
                'userEmail' => $payee->email,
            ]);

        if (! $response->successful()) {
            $this->fail(new NotificationServiceUnavailableException);
        }
    }
}
