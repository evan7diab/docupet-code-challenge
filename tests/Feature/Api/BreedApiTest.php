<?php

namespace Tests\Feature\Api;

use App\Models\Breed;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BreedApiTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey;

    private Type $dogType;

    private Type $catType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = config('app.api_key') ?: 'test-api-key';
        config(['app.api_key' => $this->apiKey]);

        $this->dogType = Type::create(['name' => 'Dog']);
        $this->catType = Type::create(['name' => 'Cat']);
    }

    public function test_returns_401_without_api_key(): void
    {
        $response = $this->getJson('/api/breeds');

        $response->assertStatus(401);
    }

    public function test_lists_breeds_with_valid_api_key(): void
    {
        Breed::create(['name' => 'Labrador', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        Breed::create(['name' => 'Persian', 'type_id' => $this->catType->id, 'is_dangerous' => false]);

        $response = $this->getJson('/api/breeds', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'type_id', 'is_dangerous'],
                ],
            ]);
    }

    public function test_lists_breeds_ordered_by_name(): void
    {
        Breed::create(['name' => 'Labrador', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        Breed::create(['name' => 'Beagle', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);

        $response = $this->getJson('/api/breeds', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals('Beagle', $data[0]['name']);
        $this->assertEquals('Labrador', $data[1]['name']);
    }

    public function test_filters_breeds_by_type_id(): void
    {
        Breed::create(['name' => 'Labrador', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        Breed::create(['name' => 'Persian', 'type_id' => $this->catType->id, 'is_dangerous' => false]);

        $response = $this->getJson("/api/breeds?type_id={$this->dogType->id}", ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Labrador', $data[0]['name']);
    }

    public function test_supports_search_parameter(): void
    {
        Breed::create(['name' => 'Labrador Retriever', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        Breed::create(['name' => 'Golden Retriever', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        Breed::create(['name' => 'Beagle', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);

        $response = $this->getJson('/api/breeds?search=Retriever', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_includes_type_relationship(): void
    {
        Breed::create(['name' => 'Labrador', 'type_id' => $this->dogType->id, 'is_dangerous' => false]);

        $response = $this->getJson('/api/breeds', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type' => ['id', 'name'],
                    ],
                ],
            ]);
    }

    public function test_supports_pagination(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Breed::create(['name' => "Breed $i", 'type_id' => $this->dogType->id, 'is_dangerous' => false]);
        }

        $response = $this->getJson('/api/breeds?per_page=5', ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(200)
            ->assertJsonPath('per_page', 5)
            ->assertJsonCount(5, 'data');
    }
}
