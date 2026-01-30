<?php

namespace App\Http\Controllers;

use App\Services\PetOwnerRegistrationService;
use Illuminate\Contracts\View\View;

class PetOwnerRegistrationController extends Controller
{
    public function __construct(
        private PetOwnerRegistrationService $petOwnerRegistrationService
    ) {}

    /**
     * Show the Pet Owner registration form.
     */
    public function __invoke(): View
    {
        return $this->petOwnerRegistrationService->loadFormView();
    }
}
