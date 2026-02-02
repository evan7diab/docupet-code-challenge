<?php

namespace App\Repositories;

use App\Models\Pet;

class PetRepository implements PetRepositoryInterface
{
    public function __construct(
        private Pet $model
    ) {}

    /**
     * Create a new pet from validated form data.
     * Transforms form fields to model attributes and persists.
     */
    public function create(array $data): Pet
    {
        $breedId = $data['breed_id'] ?? null;
        $breedText = Pet::resolveBreedText($breedId, $data);

        $petData = [
            'type_id' => $data['type_id'],
            'name' => trim($data['name']),
            'sex' => $data['gender'],
            'breed_id' => $breedId,
            'breed_text' => $breedText,
            'breed_unknown' => Pet::isBreedUnknown($breedId, $breedText),
            'dob' => $data['dob'] ?? null,
            'approx_age_years' => empty($data['dob']) ? ($data['approx_age_years'] ?? null) : null,
            'is_dangerous' => Pet::resolveIsDangerous($breedId),
        ];

        return $this->model->newQuery()->create($petData);
    }
}
