<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TypeRepositoryInterface
{
    /**
     * List types with optional search and pagination.
     */
    public function list(string $search = '', int $perPage = 15): LengthAwarePaginator;
}
