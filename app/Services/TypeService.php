<?php

namespace App\Services;

use App\Repositories\TypeRepositoryInterface;
use App\Traits\NormalizesPagination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TypeService
{
    use NormalizesPagination;

    public function __construct(
        private TypeRepositoryInterface $typeRepository
    ) {}

    /**
     * List types with search and pagination.
     *
     * @param string|null $search Search term (nullable, defaults to empty string)
     * @param int|null $perPage Items per page (nullable, clamped to configured min/max)
     */
    public function list(?string $search = null, ?int $perPage = null): LengthAwarePaginator
    {
        $search = $search ?? '';
        $perPage = $this->normalizePerPage($perPage);

        return $this->typeRepository->list($search, $perPage);
    }
}
