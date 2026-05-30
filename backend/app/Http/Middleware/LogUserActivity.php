<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (\Illuminate\Support\Facades\Auth::check() && $request->isMethod('POST', 'PUT', 'PATCH', 'DELETE')) {
            activity()
                ->causedBy(\Illuminate\Support\Facades\Auth::user())
                ->withProperties(['url' => $request->fullUrl(), 'method' => $request->method()])
                ->log('User performed ' . $request->method() . ' action on ' . $request->path());
        }

        return $response;
    }
}
