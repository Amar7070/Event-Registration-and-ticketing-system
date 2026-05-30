<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    public function globalIndex(Request $request)
    {
        $organizer = auth()->user();
        $eventIds = Event::where('organizer_id', $organizer->id)->pluck('id');
        
        $query = Ticket::with(['user', 'event', 'ticketType'])
            ->whereIn('event_id', $eventIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('booking_reference', 'like', "%{$search}%");
            });
        }

        $attendees = $query->latest()->paginate(20);

        return view('organizer.attendees.global', compact('attendees'));
    }

    public function index(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $query = Ticket::with(['user', 'ticketType', 'payment'])
            ->where('event_id', $event->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('booking_reference', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ticket_type_id')) {
            $query->where('ticket_type_id', $request->ticket_type_id);
        }

        $attendees = $query->latest()->paginate(20);
        $ticketTypes = $event->ticketTypes;

        return view('organizer.attendees.index', compact('event', 'attendees', 'ticketTypes'));
    }

    public function export(Event $event)
    {
        $this->authorize('update', $event);
        // Add export logic (CSV)
    }
}
