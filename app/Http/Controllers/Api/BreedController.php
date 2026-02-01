<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BreedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function __construct(
        private BreedService $breedService
    ) {}

    /**
     * List breeds with search, type filter, and pagination.
     *
     * Query params: search (string), type_id (int, optional), per_page (int, default 15)
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $typeId = $request->has('type_id') ? (int) $request->input('type_id') : null;
        $perPage = min(max((int) $request->input('per_page', 15), 1), 100);

        $breeds = $this->breedService->list($search, $typeId, $perPage);

        return response()->json($breeds);
    }
}
