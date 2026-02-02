<?php

namespace Tests\Feature\Api;

use App\Models\Breed;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetApiTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey;

    private Type $dogType;

    private Breed $labradorBreed;

    private Breed $pitBullBreed;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiKey = config('app.api_key') ?: 'test-api-key';
        config(['app.api_key' => $this->apiKey]);

        $this->dogType = Type::create(['name' => 'Dog']);
        $this->labradorBreed = Breed::create([
            'name' => 'Labrador',
            'type_id' => $this->dogType->id,
            'is_dangerous' => false,
        ]);
        $this->pitBullBreed = Breed::create([
            'name' => 'Pit Bull',
            'type_id' => $this->dogType->id,
            'is_dangerous' => true,
        ]);
    }

    public function test_returns_401_without_api_key(): void
    {
        $response = $this->postJson('/api/pets', []);

        $response->assertStatus(401);
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->postJson('/api/pets', [], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type_id', 'name', 'gender', 'knows_dob']);
    }

    public function test_validates_type_exists(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => 9999,
            'name' => 'Buddy',
            'gender' => 'male',
            'knows_dob' => 'no',
            'approx_age_years' => 3,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type_id']);
    }

    public function test_validates_gender_values(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'invalid',
            'knows_dob' => 'no',
            'approx_age_years' => 3,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['gender']);
    }

    public function test_creates_pet_with_known_breed(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'knows_dob' => 'yes',
            'dob' => '2021-05-15',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Buddy')
            ->assertJsonPath('data.sex', 'male')
            ->assertJsonPath('data.breed_id', $this->labradorBreed->id)
            ->assertJsonPath('data.is_dangerous', false);

        $this->assertDatabaseHas('pets', [
            'name' => 'Buddy',
            'sex' => 'male',
            'breed_id' => $this->labradorBreed->id,
        ]);
    }

    public function test_creates_pet_with_dangerous_breed(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Rocky',
            'gender' => 'male',
            'breed_id' => $this->pitBullBreed->id,
            'knows_dob' => 'no',
            'approx_age_years' => 3,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.is_dangerous', true);
    }

    public function test_creates_pet_with_unknown_breed(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Shadow',
            'gender' => 'male',
            'breed_clarification' => 'unknown',
            'knows_dob' => 'no',
            'approx_age_years' => 2,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.breed_id', null)
            ->assertJsonPath('data.breed_unknown', true);
    }

    public function test_creates_pet_with_mixed_breed(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Lady',
            'gender' => 'female',
            'breed_clarification' => 'mix',
            'breed_text' => 'Labrador and Shepherd',
            'knows_dob' => 'no',
            'approx_age_years' => 4,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.breed_id', null)
            ->assertJsonPath('data.breed_text', 'Labrador and Shepherd')
            ->assertJsonPath('data.breed_unknown', false);
    }

    public function test_creates_pet_with_dob(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'knows_dob' => 'yes',
            'dob' => '2021-05-15',
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.dob', '2021-05-15T00:00:00.000000Z');
    }

    public function test_creates_pet_with_approx_age(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Max',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'knows_dob' => 'no',
            'approx_age_years' => 5,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('data.approx_age_years', 5)
            ->assertJsonPath('data.dob', null);
    }

    public function test_returns_success_message(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'knows_dob' => 'no',
            'approx_age_years' => 3,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonPath('message', __('messages.pet.registered_success'));
    }

    public function test_loads_type_and_breed_relationships(): void
    {
        $response = $this->postJson('/api/pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'knows_dob' => 'no',
            'approx_age_years' => 3,
        ], ['X-API-Key' => $this->apiKey]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'type' => ['id', 'name'],
                    'breed' => ['id', 'name'],
                ],
            ]);
    }
}
