<?php

namespace Tests\Feature\Api;

use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeApiTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = config('app.api_key') ?: 'test-api-key';
        config(['app.api_key' => $this->apiKey]);
    }

    public function test_returns_401_without_api_key(): void
    {
        $response = $this->getJson('/api/types');

        $response->assertStatus(401)
            ->assertJson(['message' => __('messages.api.key_invalid')]);
    }

    public function test_returns_401_with_invalid_api_key(): void
    {
        $response = $this->getJson('/api/types', ['X-API-Key' => 'invalid-key']);

        $response->assertStatus(401);
    }

    public function test_lists_types_with_valid_api_key(): void
    {
        Type::create(['name' => 'Dog']);
        Type::create(['name' => 'Cat']);

        $response = $this->getJson('/api/types', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name'],
                ],
            ]);
    }

    public function test_lists_types_ordered_by_name(): void
    {
        Type::create(['name' => 'Dog']);
        Type::create(['name' => 'Cat']);
        Type::create(['name' => 'Bird']);

        $response = $this->getJson('/api/types', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Bird', $data[0]['name']);
        $this->assertEquals('Cat', $data[1]['name']);
        $this->assertEquals('Dog', $data[2]['name']);
    }

    public function test_supports_search_parameter(): void
    {
        Type::create(['name' => 'Dog']);
        Type::create(['name' => 'Cat']);

        $response = $this->getJson('/api/types?search=Dog', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Dog', $data[0]['name']);
    }

    public function test_supports_pagination(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Type::create(['name' => "Type $i"]);
        }

        $response = $this->getJson('/api/types?per_page=5', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonPath('per_page', 5)
            ->assertJsonCount(5, 'data');
    }

    public function test_pagination_max_is_capped(): void
    {
        for ($i = 1; $i <= 150; $i++) {
            Type::create(['name' => "Type $i"]);
        }

        $response = $this->getJson('/api/types?per_page=200', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonPath('per_page', 100); // Max is 100
    }
}
