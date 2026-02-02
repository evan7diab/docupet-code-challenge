<?php

namespace App\Services;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Type;
use Illuminate\Contracts\View\View;

class PetOwnerRegistrationService
{
    /**
     * Load and return the Pet Owner registration form view with types and breeds.
     */
    public function loadFormView(): View
    {
        $types = Type::orderBy('name')->get();
        $breeds = Breed::with('type')->orderBy('name')->get();

        return view('blades.pet-owner-form', [
            'types' => $types,
            'breeds' => $breeds,
        ]);
    }

    /**
     * Save pet from form values.
     * Maps form data to Pet model, snapshots is_dangerous from breed when applicable.
     */
    public function savePet(array $data): Pet
    {
        $breedId = ! empty($data['breed_id']) ? (int) $data['breed_id'] : null;
        $breedText = null;
        $breedUnknown = false;

        if (! $breedId) {
            $clarification = $data['breed_clarification'] ?? null;
            $breedUnknown = $clarification === 'unknown';
            if ($clarification === 'mix') {
                $breedText = isset($data['breed_text']) ? trim($data['breed_text']) : 'Mixed';
            }
        }

        $isDangerous = false;
        if ($breedId) {
            $breed = Breed::find($breedId);
            $isDangerous = $breed ? $breed->is_dangerous : false;
        }

        $dob = null;
        $approxAgeYears = null;
        if (! empty($data['dob']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['dob'])) {
            $dob = $data['dob'];
        }
        if ($dob === null && ! empty($data['approx_age_years'])) {
            $approx = (int) $data['approx_age_years'];
            $approxAgeYears = ($approx >= 1 && $approx <= 20) ? $approx : null;
        }

        $petData = [
            'type_id' => (int) $data['type_id'],
            'name' => trim($data['name'] ?? ''),
            'sex' => in_array($data['gender'] ?? '', ['male', 'female']) ? $data['gender'] : 'female',
            'breed_id' => $breedId,
            'breed_text' => $breedText,
            'breed_unknown' => $breedUnknown,
            'dob' => $dob,
            'approx_age_years' => $approxAgeYears,
            'is_dangerous' => $isDangerous,
        ];

        return Pet::create($petData);
    }
}
