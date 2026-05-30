<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                return (object) [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $request->session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'user_agent' => $session->user_agent,
                ];
            });

        return view('profile.sessions', compact('sessions'));
    }

    public function destroy(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('This password does not match our records.'),
            ]);
        }

        Auth::logoutOtherDevices($request->password);
        
        // Also delete from sessions table manually if using database driver
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        activity()->causedBy(Auth::user())->log('logged out from other devices');

        return back()->with('status', 'Logged out from other devices.');
    }
}
