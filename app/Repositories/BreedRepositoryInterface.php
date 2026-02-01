<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BreedRepositoryInterface
{
    /**
     * List breeds with optional search, type filter, and pagination.
     */
    public function list(string $search = '', ?int $typeId = null, int $perPage = 15): LengthAwarePaginator;
}
