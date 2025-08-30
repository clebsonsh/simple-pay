<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UnauthorizedTransferException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransferPostRequest;
use App\Services\Api\V1\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TransferController extends Controller
{
    public function __invoke(TransferPostRequest $request, TransferService $transferService): JsonResponse
    {
        /** @var string[] $data */
        $data = $request->validated();
        try {
            $transfer = $transferService->send($data);

            return response()->json($transfer, Response::HTTP_CREATED);
        } catch (UnauthorizedTransferException) {
            return response()->json([
                'error' => 'you are not authorized to make this transfer',
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'error' => 'we could not process your transfer, try agin',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
