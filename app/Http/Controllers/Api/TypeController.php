<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TypeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function __construct(
        private TypeService $typeService
    ) {}

    /**
     * List types with search and pagination.
     *
     * Query params: search (string), per_page (int, default 15)
     */
    public function index(Request $request): JsonResponse
    {
        $types = $this->typeService->list(
            search: $request->input('search'),
            perPage: $request->integer('per_page') ?: null
        );

        return response()->json($types);
    }
}
