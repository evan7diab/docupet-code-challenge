<?php

namespace App\Traits;

trait NormalizesPagination
{
    /**
     * Normalize per_page value to be within configured bounds.
     */
    private function normalizePerPage(?int $perPage): int
    {
        $default = config('pagination.default_per_page');
        $min = config('pagination.min_per_page');
        $max = config('pagination.max_per_page');

        $perPage = $perPage ?? $default;

        return min(max($perPage, $min), $max);
    }
}
