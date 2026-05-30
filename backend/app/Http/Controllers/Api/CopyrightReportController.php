<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\CopyrightReport;

class CopyrightReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = CopyrightReport::with(['user:id,name', 'event:id,title,organizer_id'])->latest()->get();
        return response()->json($reports);
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'description' => 'required|string|max:2000',
            'evidence_url' => 'nullable|url|max:255',
        ]);

        $user = $request->user();

        // Check if user is organizer or admin
        if (!in_array($user->roles->pluck('name')->first(), ['organizer', 'admin'])) {
            return response()->json(['message' => 'Only organizers and admins can file copyright claims.'], 403);
        }

        // Prevent organizer from reporting their own event
        if ($user->id === $event->organizer_id) {
            return response()->json(['message' => 'You cannot file a copyright claim against your own event.'], 403);
        }

        $report = CopyrightReport::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'subject' => 'Copyright Claim against ' . $event->title,
            'description' => $request->description,
            'evidence_url' => $request->evidence_url,
            'status' => 'pending'
        ]);

        // Send notifications to duplicate organizer and admin
        // Note: You can create dedicated Notification classes, or just use the DB directly or existing ones.
        // For simplicity, we create notifications in the database.
        
        $adminUser = \App\Models\User::whereHas('roles', function($q) { $q->where('name', 'admin'); })->first();
        if ($adminUser) {
            $adminUser->notifications()->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\CopyrightClaimCreated',
                'data' => [
                    'message' => 'New copyright claim filed against event: ' . $event->title,
                    'event_id' => $event->id,
                    'report_id' => $report->id
                ],
            ]);
        }

        if ($event->organizer) {
            $event->organizer->notifications()->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\CopyrightClaimReceived',
                'data' => [
                    'message' => 'A copyright claim has been filed against your event: ' . $event->title,
                    'event_id' => $event->id,
                    'report_id' => $report->id
                ],
            ]);
        }

        return response()->json(['message' => 'Copyright claim submitted successfully', 'report' => $report], 201);
    }

    public function update(Request $request, CopyrightReport $report)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,dismissed',
        ]);

        $report->update(['status' => $request->status]);

        // Notify the reporter
        if ($report->user) {
            $report->user->notifications()->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\CopyrightClaimUpdated',
                'data' => [
                    'message' => 'Admin has ' . $request->status . ' your copyright claim against event: ' . ($report->event ? $report->event->title : 'Unknown'),
                    'report_id' => $report->id,
                    'status' => $request->status
                ],
            ]);
        }

        // Notify the reported event's organizer
        if ($report->event && $report->event->organizer) {
            $report->event->organizer->notifications()->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\CopyrightClaimUpdated',
                'data' => [
                    'message' => 'Admin has ' . $request->status . ' the copyright claim filed against your event: ' . $report->event->title,
                    'report_id' => $report->id,
                    'status' => $request->status
                ],
            ]);
        }

        return response()->json(['message' => 'Claim status updated successfully', 'report' => $report]);
    }
}
