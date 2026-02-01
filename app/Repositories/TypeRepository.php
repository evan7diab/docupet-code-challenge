<?php

namespace App\Repositories;

use App\Models\Type;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TypeRepository implements TypeRepositoryInterface
{
    public function __construct(
        private Type $model
    ) {}

    public function list(string $search = '', int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->orderBy('name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
}
