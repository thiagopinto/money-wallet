<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{

    public function sendNotification(User $user, float $amount): bool
    {
        try {
            $response = Http::timeout(10)->post(env('NOTIFICATION_URL'), [
                'email' => $user->email,
                'message' => "Você recebeu uma transferência de R$ " . number_format($amount, 2, ',', '.')
            ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('Notification service failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Notification service exception', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
