<?php

namespace App\Services;

use Illuminate\Contracts\View\View;

class PetOwnerRegistrationService
{
    /**
     * Load and return the Pet Owner registration form view.
     */
    public function loadFormView(): View
    {
        return view('pet-owner-form');
    }
}
