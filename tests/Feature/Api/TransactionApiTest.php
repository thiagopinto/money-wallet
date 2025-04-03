<?php

namespace Tests\Feature\Api;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_validation_errors()
    {
        $response = $this->postJson('/api/v1/transactions', [
            'value' => -100,
            'payer' => 999, // ID inexistente
            'payee' => 999  // ID inexistente
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'value', 'payer', 'payee'
            ]);
    }

    /** @test */
    public function it_returns_proper_response_structure()
    {
        $payer = User::factory()->create([
            'user_type' => UserType::COMMON,
            'balance' => 1000
        ]);

        $payee = User::factory()->create();

        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response(['message' => 'Autorizado'], 200),
            'https://util.devi.tools/api/v1/notify' => Http::response([], 200)
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'value' => 100,
            'payer' => $payer->id,
            'payee' => $payee->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'amount',
                    'payer',
                    'payee',
                    'status',
                    'created_at'
                ],
                'message'
            ]);
    }
}