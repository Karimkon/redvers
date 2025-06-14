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
        // Not logged in
        if (!Auth::check()) {
            Log::warning('⛔ Not authenticated');
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        $userRole = Auth::user()->role;

        // Role mismatch
        if (!in_array($userRole, $roles)) {
            Log::warning('❌ Role failed', ['role' => $userRole, 'expected' => $roles]);

            // Optional: redirect to their actual dashboard
            if ($userRole === 'admin') return redirect()->route('admin.dashboard');
            if ($userRole === 'agent') return redirect()->route('agent.dashboard');
            if ($userRole === 'rider') return redirect()->route('rider.dashboard');
            if ($userRole === 'finance') return redirect()->route('finance.dashboard');

            return abort(403, 'Forbidden access');
        }

        return $next($request);
    }
}
