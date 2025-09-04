<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UnauthorizedTransferException;
use App\Exceptions\WrongUserTypeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransferPostRequest;
use App\Services\Api\V1\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TransferController extends Controller
{
    public function __invoke(TransferPostRequest $request, TransferService $transferService): JsonResponse
    {
        /** @var string[] $data */
        $data = $request->validated();
        try {
            return response()->json($transferService->send($data), Response::HTTP_CREATED);
        } catch (WrongUserTypeException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (UnauthorizedTransferException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        } catch (\Throwable $th) {
            Log::error($th);

            return response()->json([
                'error' => 'we could not process your transfer, try agin',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
