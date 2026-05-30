<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'timezone' => $user->timezone ?? 'Asia/Kolkata',
                'preferred_language' => $user->preferred_language ?? 'en',
                'role' => $user->roles->pluck('name')->first() ?? 'user',
                'is_approved' => $user->is_approved,
                'two_factor_enabled' => $user->two_factor_enabled,
                'avatar' => $user->avatar,
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'timezone' => 'required|string',
            'preferred_language' => 'required|string|in:en,hi',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $oldPath = str_replace('/storage/', '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        $user->fill([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'timezone' => $validated['timezone'],
            'preferred_language' => $validated['preferred_language'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        activity()->causedBy($user)->log('updated profile parameters');

        return response()->json([
            'message' => 'Profile parameters updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'timezone' => $user->timezone,
                'preferred_language' => $user->preferred_language,
                'role' => $user->roles->pluck('name')->first() ?? 'user',
                'is_approved' => $user->is_approved,
                'two_factor_enabled' => $user->two_factor_enabled,
                'avatar' => $user->avatar,
            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        activity()->causedBy($request->user())->log('updated account password');

        return response()->json([
            'message' => 'Password updated successfully.'
        ]);
    }

    public function applyOrganizer(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('organizer') || $user->hasRole('admin')) {
            return response()->json(['message' => 'You already have host governance privileges.'], 400);
        }

        $user->syncRoles(['organizer']);
        $user->update(['is_approved' => false]);

        activity()->causedBy($user)->log('applied for organizer role');

        return response()->json([
            'message' => 'Your organizer application has been submitted to the Governance Council. Please await verification.'
        ]);
    }

    public function sessions(Request $request)
    {
        $user = $request->user();
        
        // 1. Fetch DB sessions if available
        $sessions = [];
        try {
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($s) use ($request) {
                    $isCurrent = false;
                    if ($request->hasSession()) {
                        $isCurrent = $s->id === $request->session()->getId();
                    }
                    return [
                        'id' => $s->id,
                        'ip_address' => $s->ip_address,
                        'is_current_device' => $isCurrent,
                        'last_active' => \Carbon\Carbon::createFromTimestamp($s->last_activity)->diffForHumans(),
                        'user_agent' => $s->user_agent,
                        'type' => 'session_cookie'
                    ];
                })->toArray();
        } catch (\Exception $e) {
            // Sessions table doesn't exist or is not queryable
        }

        // 2. Fetch API tokens (Sanctum tokens)
        $tokens = $user->tokens->map(function($t) use ($request) {
            $isCurrent = false;
            $currentToken = $request->bearerToken();
            if ($currentToken) {
                // simple hash comparison or token id check
                $isCurrent = hash('sha256', $currentToken) === $t->token;
            }
            return [
                'id' => $t->id,
                'ip_address' => 'API Endpoint',
                'is_current_device' => $isCurrent,
                'last_active' => $t->last_used_at ? $t->last_used_at->diffForHumans() : 'Never',
                'user_agent' => $t->name,
                'type' => 'api_token'
            ];
        })->toArray();

        return response()->json([
            'sessions' => array_merge($sessions, $tokens)
        ]);
    }

    public function destroySessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out from other web session devices
        Auth::logoutOtherDevices($request->password);
        
        try {
            $currentSessionId = $request->hasSession() ? $request->session()->getId() : null;
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->when($currentSessionId, function($q) use ($currentSessionId) {
                    return $q->where('id', '!=', $currentSessionId);
                })
                ->delete();
        } catch (\Exception $e) {
            // Ignore if session table not supported
        }

        // Delete all other Sanctum API tokens except current one
        $currentToken = $request->user()->currentAccessToken();
        if ($currentToken) {
            $user->tokens()->where('id', '!=', $currentToken->id)->delete();
        } else {
            $user->tokens()->delete();
        }

        activity()->causedBy($user)->log('logged out other devices');

        return response()->json([
            'message' => 'Logged out from other devices successfully.'
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete all tokens
        $user->tokens()->delete();

        // Delete the user
        $user->delete();

        return response()->json([
            'message' => 'Your account has been deleted successfully.'
        ]);
    }
}
