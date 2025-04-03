<?php

use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Rotas de Transação
    Route::post('/transactions', [TransactionController::class, 'transfer'])
        ->name('transactions.transfer');
    
    // Futuras rotas podem ser adicionadas aqui
    // Route::get('/transactions', [TransactionController::class, 'index']);
    // Route::get('/users/{user}/balance', [UserController::class, 'balance']);
});