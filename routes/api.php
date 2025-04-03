<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Rotas de Transação
    Route::post('/transactions', [TransactionController::class, 'transactions']);
    
    // Futuras rotas podem ser adicionadas aqui
    Route::get('/transactions', function () {
        return response()->json(['message' => 'I am live!🌞']);
    });
});