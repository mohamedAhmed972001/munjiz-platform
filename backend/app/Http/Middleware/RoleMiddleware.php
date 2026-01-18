<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1️⃣ لازم يكون عامل login
        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        // 2️⃣ لازم يكون عنده role من المطلوب
        if (! $user->hasAnyRole($roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized role'
            ], 403);
        }

        return $next($request);
    }
}
