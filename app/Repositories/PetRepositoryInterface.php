<?php

namespace App\Repositories;

use App\Models\Pet;

interface PetRepositoryInterface
{
    /**
     * Create a new pet from validated form data.
     * Transforms form fields to model attributes and persists.
     */
    public function create(array $data): Pet;
}
