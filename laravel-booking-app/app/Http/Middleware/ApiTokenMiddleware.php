<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        $token = $request->header('X-API-TOKEN');

        // For simplicity, we're using a hardcoded token
        // In a real application, you would validate against a database
        if ($token !== config('app.api_token')) {
            return response()->json([
                'message' => 'Unauthorized. Invalid API token.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}