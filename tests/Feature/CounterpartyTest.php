<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Counterparty;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CounterpartyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_list_of_counterparties()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'sashapozhidaev07@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        Counterparty::create([
            'name' => 'test',
            'ogrn' => '123',
            'address' => 'address',
            'user_id' => $user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/counterparty');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'counterparties' => [
                    '*' => ['id', 'name', 'ogrn', 'address', 'user_id']
                ]
            ]);
    }
    public function test_can_create_counterparty()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'sashapozhidaev07@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $payload = [
            'inn' => '7719402047',
            'user_id' => $user->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/counterparty/store', $payload);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'counterparty' => ['id', 'name', 'ogrn', 'address', 'user_id']
            ]);
    }

    public function test_cannot_create_counterparty_with_invalid_inn()
    {
        $user = User::factory()->create();

        $payload = [
            'inn' => '123456',
            'user_id' => $user->id
        ];

        $response = $this->postJson('/api/counterparties', $payload);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
