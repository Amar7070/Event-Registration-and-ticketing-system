<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerApprovedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->hasRole('organizer') && !\Illuminate\Support\Facades\Auth::user()->is_approved) {
            return redirect()->route('dashboard')->with('error', 'Your organizer account is pending approval.');
        }
        return $next($request);
    }
}
