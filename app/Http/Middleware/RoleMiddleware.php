<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
       if (!Auth::check()) {
            Log::warning('Not authenticated');
            abort(403);
        }

        if (!in_array(Auth::user()->role, $roles)) {
            Log::warning('Role failed', ['role' => Auth::user()->role, 'expected' => $roles]);
            abort(403);
        }

        return $next($request);
    }
}
