<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use RefreshDatabase;

    private const API_KEY = '102022400310';

    public function test_accounts_requires_api_key(): void
    {
        $this->getJson('/api/v1/accounts')
            ->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Unauthorized',
                'errors' => null,
            ]);
    }

    public function test_accounts_collection_uses_success_wrapper(): void
    {
        Account::create([
            'account_number' => 'ACC-001',
            'name' => 'Test Account',
            'email' => 'account@example.com',
            'balance' => 10000,
            'status' => 'active',
            'role' => 'customer',
        ]);

        $this->withHeader('X-IAE-KEY', self::API_KEY)
            ->getJson('/api/v1/accounts')
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('meta.service_name', 'Account-Service')
            ->assertJsonPath('data.0.account_number', 'ACC-001');
    }

    public function test_missing_account_uses_error_wrapper(): void
    {
        $this->withHeader('X-IAE-KEY', self::API_KEY)
            ->getJson('/api/v1/accounts/999999')
            ->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Account not found',
                'errors' => null,
            ]);
    }

    public function test_account_can_be_created_with_api_key_only(): void
    {
        $payload = [
            'account_number' => '102022400310',
            'name' => 'Febriyan Maulana Ibrahim',
            'email' => 'febriyan@example.com',
            'balance' => 50000,
            'status' => 'active',
        ];

        $this->withHeader('X-IAE-KEY', self::API_KEY)
            ->postJson('/api/v1/accounts', $payload)
            ->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Account created successfully')
            ->assertJsonPath('data.account_number', '102022400310')
            ->assertJsonPath('meta.api_version', 'v1');

        $this->assertDatabaseHas('accounts', [
            'account_number' => '102022400310',
            'email' => 'febriyan@example.com',
        ]);
    }

    public function test_invalid_account_payload_returns_contract_error(): void
    {
        $this->withHeader('X-IAE-KEY', self::API_KEY)
            ->postJson('/api/v1/accounts', [
                'account_number' => '',
                'name' => '',
                'email' => 'not-an-email',
            ])
            ->assertStatus(422)
            ->assertJsonPath('status', 'error')
            ->assertJsonPath('message', 'Validation failed')
            ->assertJsonStructure(['errors' => ['account_number', 'name', 'email']]);
    }

    public function test_graphql_accounts_query_still_works(): void
    {
        Account::create([
            'account_number' => 'GQL-001',
            'name' => 'GraphQL Account',
            'email' => 'graphql@example.com',
            'balance' => 15000,
            'status' => 'active',
            'role' => 'customer',
        ]);

        $this->postJson('/graphql', [
            'query' => '{ accounts { account_number name email status } }',
        ])
            ->assertOk()
            ->assertJsonPath('data.accounts.0.account_number', 'GQL-001');
    }
}
