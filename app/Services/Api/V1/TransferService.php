<?php

namespace App\Services\Api\V1;

use App\Enums\UserType;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\UnauthorizedTransferException;
use App\Exceptions\WrongUserTypeException;
use App\Models\Transfer;
use App\Repositories\Api\V1\TransferRepository;
use App\Repositories\Api\V1\UserRepository;
use Illuminate\Support\Facades\DB;
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
     * @throws UnauthorizedTransferException|WrongUserTypeException|Throwable
     */
    public function send(array $data): ?Transfer
    {
        return DB::transaction(function () use ($data) {
            $value = (int) $data['value'];

            $payer_id = $data['payer'];
            $payee_id = $data['payee'];

            $payer = $this->userRepository->getById($payer_id);

            /** @var UserType */
            $userType = $payer->type;

            if ($userType === UserType::Merchant) {
                throw new WrongUserTypeException;
            }

            if ($payer->balance < $value) {
                throw new InsufficientBalanceException;
            }

            throw_unless($this->authorizationService->check(), new UnauthorizedTransferException);

            $transfer = $this->transferRepository
                ->create($payer_id, $payee_id, $value);

            $this->userRepository
                ->decrementBalanceById($payer_id, $value);

            $this->userRepository
                ->incrementBalanceById($payee_id, $value);

            return $transfer;
        });
    }
}
