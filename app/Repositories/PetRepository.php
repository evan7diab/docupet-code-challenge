<?php

namespace App\Repositories;

use App\Models\Pet;

class PetRepository implements PetRepositoryInterface
{
    public function __construct(
        private Pet $model
    ) {}

    public function create(array $data): Pet
    {
        return $this->model->newQuery()->create($data);
    }
}
