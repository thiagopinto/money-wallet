<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    ) {}

    public function transfer(TransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->transfer($request->validated());
            
            return response()->json([
                'success' => true,
                'data' => new TransactionResource($transaction),
                'message' => 'Transferência realizada com sucesso'
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}