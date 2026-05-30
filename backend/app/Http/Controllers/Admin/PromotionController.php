<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventPromotion;
use App\Models\EventPromotionPlan;
use App\Models\Event;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = EventPromotionPlan::latest()->get();
        $promotions = EventPromotion::with(['event.organizer', 'plan'])
            ->latest()
            ->paginate(10, ['*'], 'promotions_page');

        // Fetch upcoming published events for manual slideshow control
        $upcomingEvents = Event::published()
            ->upcoming()
            ->where('is_restricted', false)
            ->with(['category', 'venue', 'promotions'])
            ->latest()
            ->paginate(10, ['*'], 'events_page');

        // Compute summary metrics for the Admin view
        $totalEarned = EventPromotion::where('payment_status', 'paid')->sum('amount_paid');
        $pendingCount = EventPromotion::where('status', 'pending')->count();
        
        // Active slideshow count includes manually featured events plus active approved paid promotions
        $activeCount = Event::published()
            ->upcoming()
            ->where('is_restricted', false)
            ->where(function($query) {
                $query->where('is_featured', true)
                      ->orWhereHas('promotions', function($q) {
                          $q->where('status', 'approved')
                            ->where('payment_status', 'paid')
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                      });
            })
            ->count();

        return view('admin.promotions.index', compact('plans', 'promotions', 'upcomingEvents', 'totalEarned', 'pendingCount', 'activeCount'));
    }

    /**
     * Approve showcase request.
     */
    public function approve(Request $request, $id)
    {
        $promotion = EventPromotion::findOrFail($id);
        $duration = $promotion->plan->duration_days;

        $promotion->update([
            'status' => 'approved',
            'start_date' => now(),
            'end_date' => now()->addDays($duration),
            'payment_status' => 'paid',
        ]);

        return back()->with('success', 'Event showcase approved and assigned to the homepage slider successfully!');
    }

    /**
     * Reject showcase request.
     */
    public function reject(Request $request, $id)
    {
        $promotion = EventPromotion::findOrFail($id);

        $promotion->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Showcase request rejected successfully.');
    }

    /**
     * Manually add an event to the slideshow.
     */
    public function addToSlideshow($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['is_featured' => true]);

        return back()->with('success', "Event '{$event->title}' has been manually added to the slideshow.");
    }

    /**
     * Manually remove an event from the slideshow.
     */
    public function removeFromSlideshow($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['is_featured' => false]);

        // Also cancel/expire any active promotions for this event so it gets removed
        EventPromotion::where('event_id', $event->id)
            ->where('status', 'approved')
            ->update([
                'status' => 'expired',
                'end_date' => now(),
            ]);

        return back()->with('success', "Event '{$event->title}' has been removed from the slideshow.");
    }
}
