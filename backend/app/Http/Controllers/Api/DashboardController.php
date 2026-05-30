<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Waitlist;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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



        // 6. Next Upcoming Confirmed Ticket
        $upcomingTicketModel = Ticket::with(['event.venue', 'event.category'])
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

        $upcomingTicket = null;
        if ($upcomingTicketModel) {
            $upcomingTicket = [
                'id' => $upcomingTicketModel->id,
                'booking_reference' => $upcomingTicketModel->booking_reference,
                'quantity' => $upcomingTicketModel->quantity,
                'status' => $upcomingTicketModel->status,
                'event_title' => $upcomingTicketModel->event->title,
                'event_date' => $upcomingTicketModel->event->start_date->format('Y-m-d'),
                'event_time' => $upcomingTicketModel->event->start_date->format('g:i A'),
                'event_category' => $upcomingTicketModel->event->category?->name ?? 'Uncategorized',
                'event_city' => $upcomingTicketModel->event->venue ? $upcomingTicketModel->event->venue->city : 'Online',
                'event_venue' => $upcomingTicketModel->event->venue ? $upcomingTicketModel->event->venue->name : 'Digital Realm',
                'event_banner' => $upcomingTicketModel->event->banner,
                'ticket_type' => $upcomingTicketModel->ticketType?->name ?? 'General',
                'amount' => $upcomingTicketModel->total_amount,
            ];
        }

        // 7. Recent tickets list
        $recentTicketsModels = Ticket::with(['event.venue', 'event.category'])
            ->where('user_id', $userId)
            ->latest()
            ->limit(3)
            ->get();

        $recentTickets = $recentTicketsModels->map(function ($t) {
            return [
                'id' => $t->id,
                'booking_reference' => $t->booking_reference,
                'quantity' => $t->quantity,
                'status' => $t->status,
                'event_title' => $t->event->title,
                'event_date' => $t->event->start_date->format('M d, Y'),
                'event_time' => $t->event->start_date->format('g:i A'),
                'ticket_type' => $t->ticketType?->name ?? 'General',
                'amount' => $t->total_amount,
            ];
        });

        // 8. All confirmed tickets for calendar display
        $confirmedTicketsModels = Ticket::with(['event.venue', 'event.category'])
            ->where('user_id', $userId)
            ->where('status', 'confirmed')
            ->get();

        $confirmedTickets = $confirmedTicketsModels->map(function ($t) {
            return [
                'id' => $t->id,
                'booking_reference' => $t->booking_reference,
                'quantity' => $t->quantity,
                'status' => $t->status,
                'event_title' => $t->event->title,
                'event_date' => $t->event->start_date->format('Y-m-d'),
                'event_time' => $t->event->start_date->format('g:i A'),
                'event_category' => $t->event->category?->name ?? 'Uncategorized',
                'event_city' => $t->event->venue ? $t->event->venue->city : 'Online',
                'event_venue' => $t->event->venue ? $t->event->venue->name : 'Digital Realm',
                'event_banner' => $t->event->banner,
                'ticket_type' => $t->ticketType?->name ?? 'General',
                'amount' => $t->total_amount,
            ];
        });

        return response()->json([
            'user' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'role' => Auth::user()->roles->pluck('name')->first() ?? 'user',
                'is_approved' => Auth::user()->is_approved,
            ],
            'stats' => [
                'activeTicketsCount' => $activeTicketsCount,
                'waitlistsCount' => $waitlistsCount,
                'savedCount' => $savedCount,
                'totalGatheringsCount' => $totalGatheringsCount,

            ],
            'upcomingTicket' => $upcomingTicket,
            'recentTickets' => $recentTickets,
            'confirmedTickets' => $confirmedTickets,
        ]);
    }
}
