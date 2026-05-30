<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            $user = User::where($provider . '_id', $socialUser->getId())
                        ->orWhere('email', $socialUser->getEmail())
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    $provider . '_id' => $socialUser->getId(),
                    'password' => bcrypt(Str::random(16)),
                    'avatar' => $socialUser->getAvatar(),
                ]);
                $user->assignRole('attendee');
            } else {
                // Update social id if logging in with same email but different provider
                if (!$user->{$provider . '_id'}) {
                    $user->update([$provider . '_id' => $socialUser->getId()]);
                }
            }

            Auth::login($user);
            activity()->causedBy($user)->log('logged in via ' . $provider);

            return redirect()->intended(route('events.index', absolute: false));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }
    }
}
