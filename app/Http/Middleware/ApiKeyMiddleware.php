<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-IAE-KEY');

        $validKeys = array_filter(array_map(
            'trim',
            explode(',', env('IAE_API_KEYS', env('IAE_API_KEY', '102022400310')))
        ));

        if (!in_array($apiKey, $validKeys, true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'errors' => null
            ], 401);
        }

        return $next($request);
    }
}
