<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetSaveRequest;
use App\Services\PetOwnerRegistrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

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

    /**
     * Save the pet from form submission.
     */
    public function store(PetSaveRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pet = $this->petOwnerRegistrationService->savePet($validated);

        return redirect()
            ->route('pet-owner.register')
            ->with('success', __('messages.pet.registered', ['name' => $pet->name]));
    }
}
