<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerApprovalController extends Controller
{
    public function index()
    {
        $organizers = User::role('organizer')
            ->where('is_approved', false)
            ->latest()
            ->paginate(15);
            
        return view('admin.organizers.pending', compact('organizers'));
    }

    public function approve(User $organizer)
    {
        if (!$organizer->hasRole('organizer')) {
            abort(404);
        }

        $organizer->is_approved = true;
        $organizer->save();

        try {
            \Mail::to($organizer->email)->send(new \App\Mail\OrganizerApprovalMail($organizer));
        } catch (\Throwable $e) {
            // Keep approval even if email delivery fails.
        }

        return back()->with('success', 'Organizer approved successfully.');
    }

    public function reject(Request $request, User $organizer)
    {
        $request->validate(['reason' => 'required|string']);

        if (!$organizer->hasRole('organizer')) {
            abort(404);
        }

        try {
            \Mail::to($organizer->email)->send(new \App\Mail\OrganizerRejectionMail($organizer, $request->reason));
        } catch (\Throwable $e) {
            // Important: do not fail on notification send.
        }

        return back()->with('success', 'Organizer application rejected.');
    }
}
