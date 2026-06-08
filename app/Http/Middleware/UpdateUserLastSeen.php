<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeen
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Limit the last_seen_at updates to once per minute to prevent excessive database writes
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinute())) {
                $user->last_seen_at = now();
                $user->timestamps = false; // Disable updated_at auto-updating
                $user->save();
            }
        }

        return $next($request);
    }
}
