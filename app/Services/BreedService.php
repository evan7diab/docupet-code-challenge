<?php

namespace App\Services;

use App\Repositories\BreedRepositoryInterface;
use App\Traits\NormalizesPagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BreedService
{
    use NormalizesPagination;

    public function __construct(
        private BreedRepositoryInterface $breedRepository
    ) {}

    /**
     * List breeds with search, optional type filter, and pagination.
     *
     * @param string|null $search Search term (nullable, defaults to empty string)
     * @param int|null $typeId Filter by type ID (nullable)
     * @param int|null $perPage Items per page (nullable, clamped to configured min/max)
     */
    public function list(?string $search = null, ?int $typeId = null, ?int $perPage = null): LengthAwarePaginator
    {
        $search = $search ?? '';
        $perPage = $this->normalizePerPage($perPage);

        return $this->breedRepository->list($search, $typeId, $perPage);
    }
}
