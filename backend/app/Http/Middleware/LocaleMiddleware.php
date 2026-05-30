<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->preferred_language) {
            \Illuminate\Support\Facades\App::setLocale(\Illuminate\Support\Facades\Auth::user()->preferred_language);
        } elseif (session()->has('locale')) {
            \Illuminate\Support\Facades\App::setLocale(session('locale'));
        }
        return $next($request);
    }
}
