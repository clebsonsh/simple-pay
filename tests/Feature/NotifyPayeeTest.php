<?php

use App\Exceptions\NotificationServiceUnavailableException;
use App\Jobs\NotifyPayee;
use App\Models\User;
use App\Repositories\Api\V1\UserRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

describe('NotifyPayee', function () {
    it('successfully notifies the payee', function () {
        $url = config('services.notification_service.url').'notify';

        Http::fake([
            $url => Http::response(status: Response::HTTP_NO_CONTENT),
        ]);

        $payee = User::factory()->create();

        $job = (new NotifyPayee($payee->id))->withFakeQueueInteractions();

        $job->handle(new UserRepository);

        $job->assertNotFailed();

        Http::assertSentCount(1);
    });

    it('fails if the notification service is unreachable', function () {
        $url = config('services.notification_service.url').'notify';

        Http::fake([
            $url => Http::response(status: Response::HTTP_GATEWAY_TIMEOUT),
        ]);

        $payee = User::factory()->create();

        $job = (new NotifyPayee($payee->id))->withFakeQueueInteractions();

        $job->handle(new UserRepository);

        $job->assertFailed();
        $job->assertFailedWith(NotificationServiceUnavailableException::class);

        Http::assertSentCount(3);
    });
});
