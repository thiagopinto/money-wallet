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
            'amount' => 5,
            'payer_id' => 1, // ID inexistente
            'payee_id' => 2  // ID inexistente
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Erro de validação',
            ])
            ->assertJsonStructure([
                'errors' => [
                    'payer_id',
                    'payee_id',
                ],
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
            'https://util.devi.tools/api/v2/authorize' => Http::response([
                'status' => 'success',
                'data' => ['authorization' => true]
            ], 200),
            'https://util.devi.tools/api/v1/notify' => Http::response([], 200)
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'amount' => 100,
            'payer_id' => $payer->id,
            'payee_id' => $payee->id
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
