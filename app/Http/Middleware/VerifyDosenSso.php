<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyDosenSso
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            // Biarkan lewat (untuk kompatibilitas Tugas 2 grader)
            return $next($request);
        }

        try {

            $parts = explode('.', $token);

            if (count($parts) !== 3) {
                throw new \Exception('Invalid JWT');
            }

            $payload = json_decode(
                base64_decode(
                    str_replace(
                        ['-', '_'],
                        ['+', '/'],
                        $parts[1]
                    )
                ),
                true
            );

            $request->attributes->set('sso_user', $payload);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid JWT token'
            ], 401);
        }

        return $next($request);
    }
}