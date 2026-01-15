<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureFrontendRequestsAreStateful
{
    public function handle(Request $request, Closure $next)
    {
        $stateful = collect(explode(',', env('SANCTUM_STATEFUL_DOMAINS', '')))
            ->map(fn($d) => trim($d))
            ->filter()
            ->contains(function ($domain) use ($request) {
                return Str::contains($request->getHost().($request->getPort() ? ':' . $request->getPort() : ''), $domain);
            });

        if ($stateful) {
            $request->attributes->set('is_stateful_frontend_request', true);
        }

        return $next($request);
    }
}
