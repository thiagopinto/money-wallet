<?php

namespace App\Services;

use App\Enums\UserType;;
use App\Enums\TransactionStatus;
use App\Jobs\ProcessNotificationJob;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function __construct(
        private AuthorizationService $authorizationService,
        private NotificationService $notificationService
    ) {}

    public function transfer(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $payer = User::findOrFail($data['payer']);
            $payee = User::findOrFail($data['payee']);

            $this->validateTransfer($payer, $payee, $data['value']);

            if (!$this->authorizationService->checkAuthorization()) {
                throw new \Exception('Transferência não autorizada pelo serviço externo');
            }

            $payer->balance -= $data['value'];
            $payee->balance += $data['value'];

            $payer->save();
            $payee->save();

            $transaction = Transaction::create([
                'payer_id' => $payer->id,
                'payee_id' => $payee->id,
                'amount' => $data['value'],
                'status' => TransactionStatus::COMPLETED->value
            ]);

            ProcessNotificationJob::dispatch($payee, $data['value']);

            return $transaction;
        });
    }

    private function validateTransfer(User $payer, User $payee, float $amount): void
    {
        if ($payer->user_type === UserType::SHOPKEEPER) {
            throw new \Exception('Lojistas não podem enviar dinheiro');
        }

        if ($payer->balance < $amount) {
            throw new \Exception('Saldo insuficiente');
        }

        if ($payer->id === $payee->id) {
            throw new \Exception('Não é possível transferir para si mesmo');
        }

        if ($amount <= 0) {
            throw new \Exception('O valor deve ser maior que zero');
        }
    }
}