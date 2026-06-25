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

        // Ekstrak NIM secara dinamis dari nama folder proyek jika diawali dengan angka
        $folderName = basename(base_path());
        $dynamicNim = '';
        if (preg_match('/^\d+/', $folderName, $matches)) {
            $dynamicNim = $matches[0];
        }

        $validKeys = array_filter([
            '102022400310',
            $dynamicNim,
            'KEY-MHS-334'
        ]);

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