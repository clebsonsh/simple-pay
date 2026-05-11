<?php

use App\Exceptions\NotificationServiceUnavailableException;
use App\Jobs\NotifyPayee;
use App\Models\Transfer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

describe('NotifyPayee', function () {
    it('successfully notifies the payee', function () {
        /** @var string */
        $baseUrl = config('services.notification_service.url');
        $url = $baseUrl.'notify';

        Http::fake([
            $url => Http::response(status: Response::HTTP_NO_CONTENT),
        ]);

        $transfer = Transfer::factory()->create();

        $job = (new NotifyPayee($transfer))->withFakeQueueInteractions();

        $job->handle();

        $job->assertNotFailed();

        Http::assertSentCount(1);
    });

    it('fails if the notification service is unreachable', function () {
        /** @var string */
        $baseUrl = config('services.notification_service.url');
        $url = $baseUrl.'notify';

        Http::fake([
            $url => Http::response(status: Response::HTTP_GATEWAY_TIMEOUT),
        ]);

        $transfer = Transfer::factory()->create();

        $job = (new NotifyPayee($transfer))->withFakeQueueInteractions();

        $job->handle();

        $job->assertFailed();
        $job->assertFailedWith(NotificationServiceUnavailableException::class);

        Http::assertSentCount(3);
    });
});
