<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show the platform-wide admin dashboard.
     */
    public function index()
    {
        $metrics = $this->analyticsService->getPlatformMetrics();
        
        return view('admin.dashboard', compact('metrics'));
    }

    /**
     * Get platform metrics via AJAX for real-time updates.
     */
    public function getMetrics()
    {
        $metrics = $this->analyticsService->getPlatformMetrics();
        return response()->json($metrics);
    }

    /**
     * Show all platform events.
     */
    public function events()
    {
        $events = \App\Models\Event::with(['organizer', 'category'])->latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show platform-wide revenue and payments.
     */
    public function revenue()
    {
        $payments = \App\Models\Payment::with(['user', 'event'])->latest()->paginate(20);
        return view('admin.revenue.index', compact('payments'));
    }

    /**
     * Toggle restriction state of an event.
     */
    public function restrict(Request $request, \App\Models\Event $event)
    {
        $validated = $request->validate([
            'restriction_reason' => 'nullable|string|max:1000',
        ]);

        $isRestricted = !$event->is_restricted;

        $event->update([
            'is_restricted' => $isRestricted,
            'restriction_reason' => $isRestricted ? ($validated['restriction_reason'] ?? 'Violation of Copyright / Illegal Content Terms') : null,
        ]);

        $message = $isRestricted 
            ? 'Event has been restricted and hidden from public platforms.' 
            : 'Event restriction has been lifted successfully.';

        return back()->with('success', $message);
    }

    /**
     * Show all submitted copyright and illegal content reports.
     */
    public function copyrightReports()
    {
        $reports = \App\Models\CopyrightReport::with(['user', 'event.organizer'])->latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Resolve or dismiss a specific copyright report.
     */
    public function resolveReport(Request $request, \App\Models\CopyrightReport $report)
    {
        $validated = $request->validate([
            'action_type' => 'required|in:resolved,dismissed',
        ]);

        $report->update([
            'status' => $validated['action_type'],
        ]);

        return back()->with('success', 'Copyright report status updated to: ' . ucfirst($validated['action_type']));
    }
}
