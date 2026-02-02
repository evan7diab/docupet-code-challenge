<?php

namespace App\Repositories;

use App\Models\Pet;

interface PetRepositoryInterface
{
    /**
     * Create a new pet from validated data.
     */
    public function create(array $data): Pet;
}
