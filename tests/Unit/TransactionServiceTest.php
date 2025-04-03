<?php

namespace Tests\Unit\Services;

use App\Enums\UserType;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    private TransactionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(TransactionService::class);
    }

    /** @test */
    public function it_transfers_money_between_users()
    {
        $payer = User::factory()->create([
            'user_type' => UserType::COMMON,
            'balance' => 1000
        ]);

        $payee = User::factory()->create([
            'balance' => 500
        ]);

        $transaction = $this->service->transfer([
            'amount' => 100,
            'payer_id' => $payer->id,
            'payee_id' => $payee->id
        ]);

        $this->assertEquals(900, $payer->fresh()->balance);
        $this->assertEquals(600, $payee->fresh()->balance);
        $this->assertEquals($payer->id, $transaction->payer_id);
        $this->assertEquals($payee->id, $transaction->payee_id);
    }

    /** @test */
    public function it_throws_exception_when_payer_is_shopkeeper()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Lojistas não podem enviar dinheiro');

        $payer = User::factory()->create([
            'user_type' => UserType::SHOPKEEPER,
            'balance' => 1000
        ]);

        $payee = User::factory()->create();

        $this->service->transfer([
            'amount' => 100,
            'payer_id' => $payer->id,
            'payee_id' => $payee->id
        ]);
    }

    // Outros testes para validar saldo insuficiente, valor negativo, etc.
}