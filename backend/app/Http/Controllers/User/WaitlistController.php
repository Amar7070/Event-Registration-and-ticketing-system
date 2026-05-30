<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use App\Services\WaitlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaitlistController extends Controller
{
    protected $waitlistService;

    public function __construct(WaitlistService $waitlistService)
    {
        $this->waitlistService = $waitlistService;
    }

    /**
     * Join the waitlist for a specific ticket type.
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
        ]);

        $ticketType = TicketType::findOrFail($request->ticket_type_id);

        try {
            $this->waitlistService->join(Auth::user(), $event, $ticketType);
            return back()->with('success', 'You have been added to the waitlist at position ' . Auth::user()->waitlists()->where('event_id', $event->id)->first()->position);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the user's waitlist entries.
     */
    public function index()
    {
        $entries = Auth::user()->waitlists()->with(['event', 'ticketType'])->latest()->paginate(10);
        return view('user.waitlist.index', compact('entries'));
    }
}
