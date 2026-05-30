<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Waitlist;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Active Tickets Count (Confirmed tickets for upcoming events)
        $activeTicketsCount = Ticket::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereHas('event', function ($q) {
                $q->where('start_date', '>=', now());
            })
            ->count();

        // 2. Pending Waitlists Count
        $waitlistsCount = Waitlist::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // 3. Saved Archetypes (tracked events / distinct registered events)
        $savedCount = Ticket::where('user_id', $userId)->distinct('event_id')->count();

        // 4. Total Gatherings (All tickets ever confirmed)
        $totalGatheringsCount = Ticket::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->count();

        // 5. Total reviews submitted
        $reviewsCount = Review::where('user_id', $userId)->count();

        // 6. Next Upcoming Confirmed Ticket
        $upcomingTicket = Ticket::with(['event.venue', 'event.category'])
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->whereHas('event', function ($q) {
                $q->where('start_date', '>=', now());
            })
            ->get()
            ->sortBy(function ($t) {
                return $t->event->start_date;
            })
            ->first();

        // 7. Recent tickets list
        $recentTickets = Ticket::with(['event.venue', 'event.category'])
            ->where('user_id', $userId)
            ->latest()
            ->limit(3)
            ->get();

        // 8. All confirmed tickets for calendar display
        $confirmedTickets = Ticket::with(['event.venue', 'event.category'])
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->get();

        return view('dashboard', compact(
            'activeTicketsCount',
            'waitlistsCount',
            'savedCount',
            'totalGatheringsCount',
            'reviewsCount',
            'upcomingTicket',
            'recentTickets',
            'confirmedTickets'
        ));
    }
}
