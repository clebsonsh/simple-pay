<?php

namespace App\Services\Api\V1;

use App\Exceptions\UnauthorizedTransferExecption;
use App\Models\Transfer;
use App\Repositories\Api\V1\TransferRepository;
use App\Repositories\Api\V1\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

readonly class TransferService
{
    public function __construct(
        private TransferRepository $transferRepository,
        private UserRepository $userRepository,
        private AuthorizationService $authorizationService,
    ) {}

    /**
     * @param  string[]  $data
     *
     * @throws UnauthorizedTransferExecption|Throwable
     */
    public function send(array $data): ?Transfer
    {
        throw_unless($this->authorizationService->check(), new UnauthorizedTransferExecption);

        $transfer = null;

        try {
            $value = (int) $data['value'];

            $payer_id = $data['payer'];
            $payee_id = $data['payee'];

            DB::beginTransaction();

            $transfer = $this->transferRepository
                ->create($payer_id, $payee_id, $value);

            $this->userRepository
                ->decrementBalanceById($payer_id, $value);

            $this->userRepository
                ->incrementBalanceById($payee_id, $value);

            DB::commit();
        } catch (Throwable $th) {
            Log::error($th);
            DB::rollBack();
        }

        return $transfer;
    }
}
