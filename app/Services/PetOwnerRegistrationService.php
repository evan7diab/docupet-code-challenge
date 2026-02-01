<?php

namespace App\Services;

use App\Models\Breed;
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
}
