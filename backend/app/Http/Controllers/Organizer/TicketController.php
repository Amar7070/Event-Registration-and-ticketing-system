<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $this->authorize('update', $event);
        $ticketTypes = $event->ticketTypes()->get();
        return view('organizer.tickets.index', compact('event', 'ticketTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('organizer.tickets.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:regular,vip,early_bird,student,group,premium',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'quantity_total' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|min:1',
            'min_per_order' => 'required|integer|min:1',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'perks' => 'nullable|array',
            'is_active' => 'boolean',
            'is_transferable' => 'boolean',
            'is_refundable' => 'boolean',
            'refund_days_before' => 'integer|min:0',
        ]);

        $event->ticketTypes()->create($validated);

        return redirect()->route('organizer.events.tickets.index', $event)
            ->with('success', 'Ticket Type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, TicketType $ticket)
    {
        $this->authorize('update', $event);
        // Ensure ticket belongs to event
        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        return view('organizer.tickets.edit', compact('event', 'ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, TicketType $ticket)
    {
        $this->authorize('update', $event);
        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:regular,vip,early_bird,student,group,premium',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'quantity_total' => 'required|integer|min:1',
            'max_per_order' => 'required|integer|min:1',
            'min_per_order' => 'required|integer|min:1',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after_or_equal:sale_starts_at',
            'perks' => 'nullable|array',
            'is_active' => 'boolean',
            'is_transferable' => 'boolean',
            'is_refundable' => 'boolean',
            'refund_days_before' => 'integer|min:0',
        ]);

        $ticket->update($validated);

        return redirect()->route('organizer.events.tickets.index', $event)
            ->with('success', 'Ticket Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketType $ticket)
    {
        $this->authorize('update', $event);
        if ($ticket->event_id !== $event->id) {
            abort(404);
        }

        if ($ticket->quantity_sold > 0) {
            return back()->with('error', 'Cannot delete ticket type with sold tickets. Deactivate it instead.');
        }

        $ticket->delete();

        return redirect()->route('organizer.events.tickets.index', $event)
            ->with('success', 'Ticket Type deleted successfully.');
    }
}
