<?php

namespace Tests\Unit;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetModelTest extends TestCase
{
    use RefreshDatabase;

    private Type $dogType;

    private Breed $labradorBreed;

    private Breed $pitBullBreed;

    protected function setUp(): void
    {
        parent::setUp();

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

    // resolveBreedText tests

    public function test_resolve_breed_text_returns_null_when_breed_id_is_set(): void
    {
        $result = Pet::resolveBreedText($this->labradorBreed->id, [
            'breed_clarification' => 'mix',
            'breed_text' => 'Some mix',
        ]);

        $this->assertNull($result);
    }

    public function test_resolve_breed_text_returns_null_when_clarification_is_unknown(): void
    {
        $result = Pet::resolveBreedText(null, [
            'breed_clarification' => 'unknown',
        ]);

        $this->assertNull($result);
    }

    public function test_resolve_breed_text_returns_breed_text_when_clarification_is_mix(): void
    {
        $result = Pet::resolveBreedText(null, [
            'breed_clarification' => 'mix',
            'breed_text' => 'Labrador and Shepherd',
        ]);

        $this->assertSame('Labrador and Shepherd', $result);
    }

    public function test_resolve_breed_text_returns_mixed_when_clarification_is_mix_but_text_empty(): void
    {
        $result = Pet::resolveBreedText(null, [
            'breed_clarification' => 'mix',
            'breed_text' => '',
        ]);

        $this->assertSame('Mixed', $result);
    }

    public function test_resolve_breed_text_returns_mixed_when_clarification_is_mix_and_text_not_provided(): void
    {
        $result = Pet::resolveBreedText(null, [
            'breed_clarification' => 'mix',
        ]);

        $this->assertSame('Mixed', $result);
    }

    public function test_resolve_breed_text_trims_whitespace(): void
    {
        $result = Pet::resolveBreedText(null, [
            'breed_clarification' => 'mix',
            'breed_text' => '  Labrador Mix  ',
        ]);

        $this->assertSame('Labrador Mix', $result);
    }

    // isBreedUnknown tests

    public function test_is_breed_unknown_returns_false_when_breed_id_is_set(): void
    {
        $result = Pet::isBreedUnknown($this->labradorBreed->id, null);

        $this->assertFalse($result);
    }

    public function test_is_breed_unknown_returns_false_when_breed_text_is_set(): void
    {
        $result = Pet::isBreedUnknown(null, 'Some mix');

        $this->assertFalse($result);
    }

    public function test_is_breed_unknown_returns_true_when_both_are_null(): void
    {
        $result = Pet::isBreedUnknown(null, null);

        $this->assertTrue($result);
    }

    // resolveIsDangerous tests

    public function test_resolve_is_dangerous_returns_false_when_breed_id_is_null(): void
    {
        $result = Pet::resolveIsDangerous(null);

        $this->assertFalse($result);
    }

    public function test_resolve_is_dangerous_returns_false_for_non_dangerous_breed(): void
    {
        $result = Pet::resolveIsDangerous($this->labradorBreed->id);

        $this->assertFalse($result);
    }

    public function test_resolve_is_dangerous_returns_true_for_dangerous_breed(): void
    {
        $result = Pet::resolveIsDangerous($this->pitBullBreed->id);

        $this->assertTrue($result);
    }

    public function test_resolve_is_dangerous_returns_false_for_non_existent_breed(): void
    {
        $result = Pet::resolveIsDangerous(9999);

        $this->assertFalse($result);
    }

    // Accessor tests

    public function test_breed_display_returns_breed_name_when_breed_id_set(): void
    {
        $pet = Pet::create([
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'sex' => 'male',
            'breed_id' => $this->labradorBreed->id,
        ]);

        $this->assertSame('Labrador', $pet->breed_display);
    }

    public function test_breed_display_returns_mixed_with_text(): void
    {
        $pet = Pet::create([
            'type_id' => $this->dogType->id,
            'name' => 'Lady',
            'sex' => 'female',
            'breed_text' => 'Lab and Shepherd',
        ]);

        $this->assertSame('Mixed: Lab and Shepherd', $pet->breed_display);
    }

    public function test_breed_display_returns_unknown_when_no_breed_info(): void
    {
        $pet = Pet::create([
            'type_id' => $this->dogType->id,
            'name' => 'Shadow',
            'sex' => 'male',
            'breed_unknown' => true,
        ]);

        $this->assertSame('Unknown', $pet->breed_display);
    }

    public function test_age_years_returns_approx_age_when_no_dob(): void
    {
        $pet = Pet::create([
            'type_id' => $this->dogType->id,
            'name' => 'Max',
            'sex' => 'male',
            'approx_age_years' => 5,
        ]);

        $this->assertSame(5, $pet->age_years);
    }

    public function test_age_years_calculates_from_dob(): void
    {
        $pet = Pet::create([
            'type_id' => $this->dogType->id,
            'name' => 'Buddy',
            'sex' => 'male',
            'dob' => now()->subYears(3)->format('Y-m-d'),
        ]);

        $this->assertSame(3, $pet->age_years);
    }
}
