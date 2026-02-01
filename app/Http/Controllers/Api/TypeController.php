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
        $search = $request->input('search', '');
        $perPage = min(max((int) $request->input('per_page', 15), 1), 100);

        $types = $this->typeService->list($search, $perPage);

        return response()->json($types);
    }
}
