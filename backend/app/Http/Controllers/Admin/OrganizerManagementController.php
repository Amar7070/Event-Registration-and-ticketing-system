<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Review;
use App\Models\CopyrightReport;
use App\Models\EventPromotion;
use Illuminate\Http\Request;

class OrganizerManagementController extends Controller
{
    /**
     * Display a listing of approved organizers.
     */
    public function index(Request $request)
    {
        $query = User::role('organizer')->where('is_approved', true);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $status = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $status);
        }

        $organizers = $query->withCount('organizedEvents')
            ->latest()
            ->paginate(10);

        // Add revenue data to each organizer in the collection
        foreach ($organizers as $organizer) {
            $eventIds = $organizer->organizedEvents->pluck('id');
            $organizer->total_revenue = Ticket::whereIn('event_id', $eventIds)
                ->where('status', 'confirmed')
                ->sum('total_amount');
        }

        return view('admin.organizers.index', compact('organizers'));
    }

    /**
     * Display the specified organizer's details, events, reviews, and revenue.
     */
    public function show(User $organizer)
    {
        // Security check
        if (!$organizer->hasRole('organizer') || !$organizer->is_approved) {
            abort(404);
        }

        $eventIds = $organizer->organizedEvents()->pluck('id');

        // Main Aggregated Metrics
        $totalEvents = $organizer->organizedEvents()->count();
        
        $ticketsQuery = Ticket::whereIn('event_id', $eventIds)->where('status', 'confirmed');
        $totalTicketsSold = $ticketsQuery->count();
        $totalRevenue = $ticketsQuery->sum('total_amount');

        $averageRating = Review::whereIn('event_id', $eventIds)->avg('rating') ?? 0;

        // Fetch events with counts and revenue
        $events = $organizer->organizedEvents()
            ->withCount(['tickets' => function ($query) {
                $query->where('status', 'confirmed');
            }])
            ->with(['category', 'venue'])
            ->latest()
            ->get();

        // Calculate revenue for each event individually
        foreach ($events as $event) {
            $event->revenue = $event->tickets()->where('status', 'confirmed')->sum('total_amount');
            $event->rating = $event->reviews()->avg('rating') ?? 0;
        }

        // Fetch promotions
        $promotions = EventPromotion::whereIn('event_id', $eventIds)
            ->with('event')
            ->latest()
            ->get();

        // Fetch reviews
        $reviews = Review::whereIn('event_id', $eventIds)
            ->with(['event', 'user'])
            ->latest()
            ->get();

        // Fetch copyright reports
        $copyrightReports = CopyrightReport::whereIn('event_id', $eventIds)
            ->with(['event', 'user'])
            ->latest()
            ->get();

        return view('admin.organizers.show', compact(
            'organizer',
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'averageRating',
            'events',
            'promotions',
            'reviews',
            'copyrightReports'
        ));
    }

    /**
     * Toggle the suspension status of an organizer.
     */
    public function toggleStatus(User $organizer)
    {
        if (!$organizer->hasRole('organizer') || !$organizer->is_approved) {
            abort(404);
        }

        $organizer->is_active = !$organizer->is_active;
        $organizer->save();

        $statusMessage = $organizer->is_active 
            ? "Host access successfully restored." 
            : "Host has been restricted and access suspended.";

        return back()->with('success', $statusMessage);
    }
}
