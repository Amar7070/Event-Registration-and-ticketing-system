<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show global analytics for all organizer events.
     */
    public function index()
    {
        $organizer = auth()->user();
        $events = Event::where('organizer_id', $organizer->id)->get();
        
        // Simple aggregation for now
        $totalRevenue = \App\Models\Payment::whereIn('event_id', $events->pluck('id'))
            ->where('status', 'completed')
            ->sum('organizer_earnings');
            
        $totalTickets = \App\Models\Ticket::whereIn('event_id', $events->pluck('id'))
            ->where('status', 'confirmed')
            ->sum('quantity');

        return view('organizer.analytics.index', compact('totalRevenue', 'totalTickets', 'events'));
    }

    /**
     * Show the analytics dashboard for a specific event.
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);
        
        $metrics = $this->analyticsService->getEventMetrics($event);
        
        return view('organizer.analytics.show', compact('event', 'metrics'));
    }

    /**
     * Get real-time stats for the dashboard via AJAX.
     */
    public function getStats(Event $event)
    {
        $this->authorize('view', $event);
        $metrics = $this->analyticsService->getEventMetrics($event);
        return response()->json($metrics);
    }
}
