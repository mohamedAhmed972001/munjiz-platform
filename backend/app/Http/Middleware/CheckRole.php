<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * معالجة الطلب والتأكد من "دور" المستخدم
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // إذا كان المستخدم مسجل دخول ولكن دوره لا يطابق الدور المطلوب
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json(['message' => 'Unauthorized. This action is for ' . $role . 's only.'], 403);
        }

        return $next($request);
    }
}