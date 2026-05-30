<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.verify-2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = Auth::user();

        if ($request->otp == $user->two_factor_secret) {
            $request->session()->put('2fa_verified', true);
            activity()->causedBy($user)->log('verified 2FA');
            return redirect()->intended(route('events.index', absolute: false));
        }

        return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
    }

    public function resend(Request $request)
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);
        $user->update(['two_factor_secret' => $otp]);

        // In a real app, send email/SMS here
        // e.g. Mail::to($user)->send(new TwoFactorOtpMail($otp));

        return back()->with('status', 'A new OTP has been sent to your email/phone.');
    }
}
