<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetSaveRequest;
use App\Services\PetOwnerRegistrationService;
use Illuminate\Http\JsonResponse;

class PetController extends Controller
{
    public function __construct(
        private PetOwnerRegistrationService $petOwnerRegistrationService
    ) {}

    /**
     * Store a new pet registration.
     */
    public function store(PetSaveRequest $request): JsonResponse
    {
        $pet = $this->petOwnerRegistrationService->savePet($request->validated());

        return response()->json([
            'message' => __('messages.pet.registered_success'),
            'data' => $pet->load(['type', 'breed']),
        ], 201);
    }
}
