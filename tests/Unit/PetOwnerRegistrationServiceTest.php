<?php

namespace Tests\Unit;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Type;
use App\Services\PetOwnerRegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetOwnerRegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    private PetOwnerRegistrationService $service;

    private Type $dogType;

    private Breed $labradorBreed;

    private Breed $pitBullBreed;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new PetOwnerRegistrationService();

        $this->dogType = Type::create(['name' => 'Dog']);
        $this->labradorBreed = Breed::create([
            'name' => 'Labrador Retriever',
            'type_id' => $this->dogType->id,
            'is_dangerous' => false,
        ]);
        $this->pitBullBreed = Breed::create([
            'name' => 'Pit Bull Terrier',
            'type_id' => $this->dogType->id,
            'is_dangerous' => true,
        ]);
    }

    public function test_saves_pet_with_known_breed(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
        ];

        $pet = $this->service->savePet($data);

        $this->assertInstanceOf(Pet::class, $pet);
        $this->assertDatabaseHas('pets', [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'sex' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'breed_text' => null,
            'breed_unknown' => false,
            'is_dangerous' => false,
        ]);
        $this->assertNull($pet->breed_text);
        $this->assertFalse($pet->breed_unknown);
        $this->assertFalse($pet->is_dangerous);
    }

    public function test_snapshots_is_dangerous_when_breed_is_dangerous(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Rocky',
            'gender' => 'male',
            'breed_id' => $this->pitBullBreed->id,
        ];

        $pet = $this->service->savePet($data);

        $this->assertTrue($pet->is_dangerous);
        $this->assertDatabaseHas('pets', [
            'name' => 'Rocky',
            'breed_id' => $this->pitBullBreed->id,
            'is_dangerous' => true,
        ]);
    }

    public function test_saves_pet_with_breed_unknown(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Shadow',
            'gender' => 'male',
            'breed_clarification' => 'unknown',
        ];

        $pet = $this->service->savePet($data);

        $this->assertNull($pet->breed_id);
        $this->assertNull($pet->breed_text);
        $this->assertTrue($pet->breed_unknown);
        $this->assertFalse($pet->is_dangerous);
    }

    public function test_saves_pet_with_breed_mix_and_text(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Lady',
            'gender' => 'female',
            'breed_clarification' => 'mix',
            'breed_text' => 'Labrador and Shepherd',
        ];

        $pet = $this->service->savePet($data);

        $this->assertNull($pet->breed_id);
        $this->assertSame('Labrador and Shepherd', $pet->breed_text);
        $this->assertFalse($pet->breed_unknown);
    }

    public function test_saves_pet_with_breed_mix_defaults_to_mixed_when_empty(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Mix',
            'gender' => 'female',
            'breed_clarification' => 'mix',
        ];

        $pet = $this->service->savePet($data);

        $this->assertSame('Mixed', $pet->breed_text);
    }

    public function test_saves_pet_with_dob(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'dob' => '2021-05-15',
        ];

        $pet = $this->service->savePet($data);

        $this->assertSame('2021-05-15', $pet->dob->format('Y-m-d'));
        $this->assertNull($pet->approx_age_years);
    }

    public function test_saves_pet_with_approx_age_years(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Max',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'approx_age_years' => 5,
        ];

        $pet = $this->service->savePet($data);

        $this->assertNull($pet->dob);
        $this->assertSame(5, $pet->approx_age_years);
    }

    public function test_defaults_gender_to_female_when_invalid(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'gender' => 'invalid',
            'breed_id' => $this->labradorBreed->id,
        ];

        $pet = $this->service->savePet($data);

        $this->assertSame('female', $pet->sex);
    }

    public function test_trims_name(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => '  Buddy  ',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
        ];

        $pet = $this->service->savePet($data);

        $this->assertSame('Buddy', $pet->name);
    }

    public function test_approx_age_years_clamped_to_valid_range(): void
    {
        $data = [
            'type_id' => $this->dogType->id,
            'name' => 'Max',
            'gender' => 'male',
            'breed_id' => $this->labradorBreed->id,
            'approx_age_years' => 25,
        ];

        $pet = $this->service->savePet($data);

        $this->assertNull($pet->approx_age_years);
    }
}
