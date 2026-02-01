<?php

namespace App\Services;

use App\Repositories\TypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TypeService
{
    public function __construct(
        private TypeRepositoryInterface $typeRepository
    ) {}

    /**
     * List types with search and pagination.
     */
    public function list(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        return $this->typeRepository->list($search, $perPage);
    }
}
