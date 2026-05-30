<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->two_factor_enabled) {
            if (!$request->session()->has('2fa_verified')) {
                // Check if current route is related to 2fa to prevent redirect loops
                if (!$request->is('verify-2fa') && !$request->is('verify-2fa/*')) {
                    // Generate OTP if not generated yet
                    if (!$user->two_factor_secret) {
                        $otp = rand(100000, 999999);
                        $user->update(['two_factor_secret' => $otp]);
                        // Send OTP...
                    }
                    return redirect()->route('verify-2fa.index');
                }
            }
        }

        return $next($request);
    }
}
