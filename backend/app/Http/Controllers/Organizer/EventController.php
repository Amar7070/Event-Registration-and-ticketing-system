<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();

        $events = Event::where('organizer_id', $organizerId)
            ->with(['category', 'venue'])
            ->latest()
            ->paginate(10);

        // Fetch dynamic organizer stats from the database
        $eventIds = Event::where('organizer_id', $organizerId)->pluck('id');

        $activeCount = Event::where('organizer_id', $organizerId)->count();

        $totalResonance = \App\Models\Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'confirmed')
            ->sum('quantity');

        $totalRevenue = \App\Models\Payment::whereIn('event_id', $eventIds)
            ->where('status', 'completed')
            ->sum('amount');

        $globalRating = 4.9; // Dynamic standard placeholder, can be loaded from Reviews model if needed

        return view('organizer.events.index', compact('events', 'activeCount', 'totalResonance', 'totalRevenue', 'globalRating'));
    }

    public function create()
    {
        $categories = EventCategory::where('is_active', true)->get();
        $venues = Venue::all();
        return view('organizer.events.create', compact('categories', 'venues'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'venue_id' => 'nullable|exists:venues,id',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'type' => 'required|in:physical,online,hybrid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_capacity' => 'required|integer|min:1',
            'visibility' => 'required|in:public,private,unlisted',
            'timezone' => 'required|string',
            'requires_approval' => 'boolean',
        ]);

        if ($request->boolean('create_new_venue')) {
            $venueValidated = $request->validate([
                'venue_name' => 'required|string|max:255',
                'venue_address' => 'required|string',
                'venue_city' => 'required|string|max:255',
                'venue_state' => 'required|string|max:255',
                'venue_country' => 'required|string|max:255',
                'venue_pincode' => 'required|string|max:10',
                'venue_capacity' => 'nullable|integer|min:1',
                'venue_latitude' => 'nullable|numeric',
                'venue_longitude' => 'nullable|numeric',
            ]);

            $venue = Venue::create([
                'name' => $venueValidated['venue_name'],
                'address' => $venueValidated['venue_address'],
                'city' => $venueValidated['venue_city'],
                'state' => $venueValidated['venue_state'],
                'country' => $venueValidated['venue_country'],
                'pincode' => $venueValidated['venue_pincode'],
                'capacity' => $venueValidated['venue_capacity'] ?? $validated['total_capacity'],
                'latitude' => $venueValidated['venue_latitude'],
                'longitude' => $venueValidated['venue_longitude'],
            ]);

            $validated['venue_id'] = $venue->id;
        }

        $event = Auth::user()->organizedEvents()->create($validated);

        if ($request->hasFile('banner')) {
            $event->addMediaFromRequest('banner')->toMediaCollection('banners');
        }

        activity()->causedBy(Auth::user())->performedOn($event)->log('created event');

        return redirect()->route('organizer.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $categories = EventCategory::where('is_active', true)->get();
        $venues = Venue::all();
        return view('organizer.events.edit', compact('event', 'categories', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:event_categories,id',
            'venue_id' => 'nullable|exists:venues,id',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'type' => 'required|in:physical,online,hybrid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_capacity' => 'required|integer|min:1',
            'visibility' => 'required|in:public,private,unlisted',
            'timezone' => 'required|string',
            'requires_approval' => 'boolean',
        ]);

        if ($request->boolean('create_new_venue')) {
            $venueValidated = $request->validate([
                'venue_name' => 'required|string|max:255',
                'venue_address' => 'required|string',
                'venue_city' => 'required|string|max:255',
                'venue_state' => 'required|string|max:255',
                'venue_country' => 'required|string|max:255',
                'venue_pincode' => 'required|string|max:10',
                'venue_capacity' => 'nullable|integer|min:1',
                'venue_latitude' => 'nullable|numeric',
                'venue_longitude' => 'nullable|numeric',
            ]);

            $venue = Venue::create([
                'name' => $venueValidated['venue_name'],
                'address' => $venueValidated['venue_address'],
                'city' => $venueValidated['venue_city'],
                'state' => $venueValidated['venue_state'],
                'country' => $venueValidated['venue_country'],
                'pincode' => $venueValidated['venue_pincode'],
                'capacity' => $venueValidated['venue_capacity'] ?? $validated['total_capacity'],
                'latitude' => $venueValidated['venue_latitude'],
                'longitude' => $venueValidated['venue_longitude'],
            ]);

            $validated['venue_id'] = $venue->id;
        }

        $event->update($validated);

        if ($request->hasFile('banner')) {
            $event->clearMediaCollection('banners');
            $event->addMediaFromRequest('banner')->toMediaCollection('banners');
        }

        activity()->causedBy(Auth::user())->performedOn($event)->log('updated event');

        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        activity()->causedBy(Auth::user())->performedOn($event)->log('deleted event');
        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully.');
    }

    public function publish(Event $event)
    {
        $this->authorize('update', $event);
        $event->update(['status' => 'published']);
        activity()->causedBy(Auth::user())->performedOn($event)->log('published event');
        return back()->with('success', 'Event published successfully.');
    }

    public function clone(Event $event)
    {
        $this->authorize('view', $event);
        $clone = $event->replicate();
        $clone->title = 'Clone of ' . $event->title;
        $clone->slug = Str::slug($clone->title) . '-' . Str::random(6);
        $clone->status = 'draft';
        $clone->save();

        activity()->causedBy(Auth::user())->performedOn($clone)->log('cloned event');

        return redirect()->route('organizer.events.edit', $clone)->with('success', 'Event cloned as draft.');
    }

    public function dashboard(Event $event)
    {
        $this->authorize('view', $event);
        // Stats logic here
        return view('organizer.events.dashboard', compact('event'));
    }

    public function cancel(Event $event, \App\Services\PaymentService $paymentService)
    {
        $this->authorize('update', $event);
        
        $event->update(['status' => 'cancelled']);
        
        // Loop through all confirmed tickets and trigger refunds
        $tickets = \App\Models\Ticket::where('event_id', $event->id)->where('status', 'confirmed')->get();
        foreach ($tickets as $ticket) {
            if ($ticket->payment_id) {
                $refund = \App\Models\Refund::create([
                    'payment_id' => $ticket->payment_id,
                    'ticket_id' => $ticket->id,
                    'requested_by' => Auth::id(),
                    'amount' => $ticket->total_amount,
                    'reason' => 'Event cancelled by organizer',
                    'status' => 'pending',
                ]);
                
                // Trigger actual refund
                $paymentService->processRefund($refund);
                $ticket->update(['status' => 'refunded']);
            }
        }

        activity()->causedBy(Auth::user())->performedOn($event)->log('cancelled event and initiated refunds');
        return back()->with('success', 'Event cancelled and refunds initiated.');
    }

    /**
     * Show the promotion purchase/showcase request form.
     */
    public function showPromoteForm(Event $event)
    {
        $this->authorize('update', $event);

        $plans = \App\Models\EventPromotionPlan::where('is_active', true)->get();
        $promotions = \App\Models\EventPromotion::where('event_id', $event->id)
            ->with('plan')
            ->latest()
            ->get();

        return view('organizer.events.promote', compact('event', 'plans', 'promotions'));
    }

    /**
     * Handle simulated payment and submit promotion request to Admin.
     */
    public function submitPromotion(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $request->validate([
            'plan_id' => 'required|exists:event_promotion_plans,id',
            'card_number' => 'required|string|min:16',
            'card_expiry' => 'required|string',
            'card_cvv' => 'required|string|min:3',
        ]);

        $plan = \App\Models\EventPromotionPlan::findOrFail($request->plan_id);

        \App\Models\EventPromotion::create([
            'event_id' => $event->id,
            'plan_id' => $plan->id,
            'amount_paid' => $plan->price,
            'payment_status' => 'paid',
            'status' => 'pending' // Awaiting admin assignment/approval
        ]);

        activity()->causedBy(Auth::user())->performedOn($event)->log('purchased showcase advertisement plan');

        return redirect()->route('organizer.events.promote', $event)
            ->with('success', 'Showcase advertisement paid successfully! Placed in queue for Admin assignment.');
    }
}
