<?php

namespace App\Http\Controllers;

use App\Services\PetOwnerRegistrationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'breed_id' => 'nullable|exists:breeds,id',
            'breed_clarification' => 'nullable|in:unknown,mix',
            'breed_text' => 'nullable|string|max:255',
            'knows_dob' => 'required|in:yes,no',
            'approx_age_years' => 'nullable|integer|min:1|max:20',
            'dob' => 'nullable|date|before_or_equal:today',
        ]);

        $pet = $this->petOwnerRegistrationService->savePet($validated);

        return redirect()
            ->route('pet-owner.register')
            ->with('success', 'Pet "' . e($pet->name) . '" has been registered.');
    }
}
