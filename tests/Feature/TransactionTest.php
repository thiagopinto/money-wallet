<?php

namespace Tests\Feature;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $commonUser;
    private User $shopkeeper;
    private User $anotherCommonUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Configura usuários de teste
        $this->commonUser = User::factory()->create([
            'user_type' => UserType::COMMON,
            'balance' => 1000.00
        ]);

        $this->shopkeeper = User::factory()->create([
            'user_type' => UserType::SHOPKEEPER,
            'balance' => 1000.00
        ]);

        $this->anotherCommonUser = User::factory()->create([
            'user_type' => UserType::COMMON,
            'balance' => 500.00
        ]);

        // Mock do serviço de autorização
        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response([
                'message' => 'Autorizado'
            ], 200),
            
            'https://util.devi.tools/api/v1/notify' => Http::response([], 200)
        ]);
    }

    /** @test */
    public function common_user_can_transfer_money_to_another_user()
    {
        $response = $this->postJson('/api/v1/transactions', [
            'value' => 100.50,
            'payer' => $this->commonUser->id,
            'payee' => $this->anotherCommonUser->id
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Transferência realizada com sucesso'
            ]);

        $this->assertEquals(899.50, $this->commonUser->fresh()->balance);
        $this->assertEquals(600.50, $this->anotherCommonUser->fresh()->balance);
    }

    /** @test */
    public function shopkeeper_cannot_send_money()
    {
        $response = $this->postJson('/api/v1/transactions', [
            'value' => 100,
            'payer' => $this->shopkeeper->id,
            'payee' => $this->anotherCommonUser->id
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Lojistas não podem enviar dinheiro'
            ]);
    }

    /** @test */
    public function cannot_transfer_with_insufficient_balance()
    {
        $response = $this->postJson('/api/v1/transactions', [
            'value' => 1500,
            'payer' => $this->commonUser->id,
            'payee' => $this->anotherCommonUser->id
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Saldo insuficiente'
            ]);
    }

    /** @test */
    public function cannot_transfer_to_yourself()
    {
        $response = $this->postJson('/api/v1/transactions', [
            'value' => 100,
            'payer' => $this->commonUser->id,
            'payee' => $this->commonUser->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['payee']);
    }

    /** @test */
    public function transfer_fails_when_authorization_service_is_down()
    {
        Http::fake([
            'https://util.devi.tools/api/v2/authorize' => Http::response([], 500)
        ]);

        $response = $this->postJson('/api/v1/transactions', [
            'value' => 100,
            'payer' => $this->commonUser->id,
            'payee' => $this->anotherCommonUser->id
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'Transferência não autorizada pelo serviço externo'
            ]);
    }
}