<?php

namespace App\Services;

use App\Repositories\BreedRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BreedService
{
    public function __construct(
        private BreedRepositoryInterface $breedRepository
    ) {}

    /**
     * List breeds with search, optional type filter, and pagination.
     */
    public function list(string $search = '', ?int $typeId = null, int $perPage = 15): LengthAwarePaginator
    {
        return $this->breedRepository->list($search, $typeId, $perPage);
    }
}
