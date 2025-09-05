<?php

use App\Jobs\NotifyPayee;
use App\Models\User;
use App\Services\Api\V1\TransferService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\postJson;

beforeEach(function () {
    Queue::fake(NotifyPayee::class);
});

describe('Transfer', function () {
    it('allows a customer to transfer to a merchant with proper authorization', function () {
        $url = config('services.authorization_service.url').'authorize';

        Http::fake([
            $url => Http::response(
                [
                    'status' => 'success',
                    'data' => ['authorization' => true],
                ], Response::HTTP_OK),
        ]);

        $payer = User::factory()->customer()->create();
        $payee = User::factory()->merchant()->create();

        $body = [
            'value' => 1000,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        postJson(route('transfer'), $body)
            ->assertCreated()
            ->assertJsonFragment(['payer_id' => $payer->id])
            ->assertJsonFragment(['payee_id' => $payee->id])
            ->assertJsonFragment(['value' => 1000]);

    });

    it('prevents a merchant from making a transfer to another user', function () {
        $payer = User::factory()->merchant()->create();
        $payee = User::factory()->merchant()->create();

        $body = [
            'value' => 1000,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        postJson(route('transfer'), $body)
            ->assertUnprocessable()
            ->assertJsonFragment(['error' => 'The payer can not be a user type merchant']);
    });

    it('prevents a users with insuficent balance from transfer', function () {
        $payer = User::factory()->customer()->create([
            'balance' => 999,
        ]);

        $payee = User::factory()->merchant()->create();

        $body = [
            'value' => 1000,
            'payer' => $payer->id,
            'payee' => $payee->id,
        ];

        postJson(route('transfer'), $body)
            ->assertUnprocessable()
            ->assertJsonFragment(['error' => 'The payer does not have enough balance to send this transfer']);
    });

    it('returns a forbidden error when the authorization service denies the transfer', function () {
        $url = config('services.authorization_service.url').'authorize';

        Http::fake([
            $url => Http::response(
                [
                    'status' => 'fail',
                    'data' => ['authorization' => false],
                ], Response::HTTP_FORBIDDEN),
        ]);

        postJson(route('transfer'), getDefaultTransferBody())
            ->assertForbidden()
            ->assertJsonFragment(['error' => 'you are not authorized to make this transfer']);
    });

    it('returns a generic 500 error when a unknow error is throw', function () {
        $transferServiceMock = Mockery::mock(TransferService::class);

        app()->instance(TransferService::class, $transferServiceMock);

        $transferServiceMock->shouldReceive('send')
            ->andThrow(new Exception('unknow exception'));

        postJson(route('transfer'), getDefaultTransferBody())
            ->assertServerError()
            ->assertJsonFragment(['error' => 'we could not process your transfer, try agin']);
    });
});

function getDefaultTransferBody(): array
{

    $payer = User::factory()->customer()->create();
    $payee = User::factory()->merchant()->create();

    return [
        'value' => 1000,
        'payer' => $payer->id,
        'payee' => $payee->id,
    ];
}
