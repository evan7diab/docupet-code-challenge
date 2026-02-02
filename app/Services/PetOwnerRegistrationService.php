<?php

namespace App\Services;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Type;
use App\Repositories\PetRepositoryInterface;
use Illuminate\Contracts\View\View;

class PetOwnerRegistrationService
{
    public function __construct(
        private PetRepositoryInterface $petRepository
    ) {}

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
     * Save pet from validated form data.
     *
     * @param array $data Validated data from PetSaveRequest
     */
    public function savePet(array $data): Pet
    {
        return $this->petRepository->create($data);
    }
}
