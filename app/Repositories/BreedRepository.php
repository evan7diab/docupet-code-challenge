<?php

namespace App\Repositories;

use App\Models\Breed;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BreedRepository implements BreedRepositoryInterface
{
    public function __construct(
        private Breed $model
    ) {}

    public function list(string $search = '', ?int $typeId = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with('type')
            ->orderBy('name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($typeId !== null) {
            $query->where('type_id', $typeId);
        }

        return $query->paginate($perPage);
    }
}
