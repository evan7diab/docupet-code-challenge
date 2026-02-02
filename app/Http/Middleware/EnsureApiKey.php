<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    /**
     * Require a valid API key. API_KEY must be set in config.
     * Accepts X-API-Key or Authorization: Bearer <key>.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('app.api_key');

        if (empty($expected)) {
            return response()->json(['message' => __('messages.api.key_not_configured')], 503);
        }

        $key = $request->header('X-API-Key')
            ?? $request->bearerToken();

        if ($key === $expected) {
            return $next($request);
        }

        return response()->json(['message' => __('messages.api.key_invalid')], 401);
    }
}
