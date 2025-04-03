<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthorizationService
{

    public function checkAuthorization(): bool
    {
        try {
            $response = Http::timeout(10)->get(env('AUTHORIZATION_URL'));
            
            if ($response->successful()) {
                return $response->json('message') === 'Autorizado';
            }
            
            Log::error('Authorization service failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;
            
        } catch (\Exception $e) {
            Log::error('Authorization service exception', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}


