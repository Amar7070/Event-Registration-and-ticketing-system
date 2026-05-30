<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function home()
    {
        // 1. Featured / Promoted Events
        $featuredEvents = Event::published()
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
            ->with(['category', 'venue', 'organizer'])
            ->latest()
            ->take(8)
            ->get();

        // 2. Event Categories
        $categories = \App\Models\EventCategory::where('is_active', true)->get();

        // 3. Top Organizers (Organizers with most events or highest rating)
        // For simplicity, we fetch top 4 approved organizers with their event count.
        $topOrganizers = \App\Models\User::role('organizer')
            ->where('is_approved', true)
            ->where('is_active', true)
            ->withCount('organizedEvents')
            ->orderBy('organized_events_count', 'desc')
            ->take(4)
            ->get(['id', 'name', 'avatar', 'created_at']);

        // 4. Testimonials (Recent 5-star reviews)
        $testimonials = \App\Models\Review::with(['user', 'event'])
            ->where('rating', '>=', 4)
            ->latest()
            ->take(5)
            ->get();

        // 5. Global Stats (Real or semi-real)
        $stats = [
            'tickets_sold' => \App\Models\Ticket::where('status', 'confirmed')->sum('quantity'),
            'active_organizers' => \App\Models\User::role('organizer')->where('is_approved', true)->count(),
            'total_events' => Event::published()->count(),
            'total_cities' => \App\Models\Venue::distinct('city')->count('city') ?? 12,
        ];

        return response()->json([
            'featured_events' => $featuredEvents,
            'categories' => $categories,
            'top_organizers' => $topOrganizers,
            'testimonials' => $testimonials,
            'stats' => $stats
        ]);
    }

    public function index(Request $request)
    {
        $query = Event::published()->upcoming()->where('is_restricted', false);

        if ($request->has('category') && $request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Date Range Search
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            // We use start_date column to filter events starting before the end_date. 
            // Can also use end_date column if we want events that finish before the end_date.
            $query->whereDate('start_date', '<=', $request->end_date);
        }

        // Geolocation Radius Search (Nearby Me with range in km)
        if ($request->filled('latitude') && $request->filled('longitude') && $request->filled('radius')) {
            $latitude = (float) $request->latitude;
            $longitude = (float) $request->longitude;
            $radius = (float) $request->radius;

            $query->whereHas('venue', function ($q) use ($latitude, $longitude, $radius) {
                $q->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->whereRaw(
                      "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                      [$latitude, $longitude, $latitude, $radius]
                  );
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->with(['category', 'venue', 'organizer'])->latest()->paginate(12);

        // Fetch actively promoted events
        $promotedEvents = Event::published()
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
            ->with(['category', 'venue', 'organizer'])
            ->latest()
            ->get();

        return response()->json([
            'data' => EventResource::collection($events)->response()->getData(true)['data'],
            'meta' => EventResource::collection($events)->response()->getData(true)['meta'],
            'promoted' => EventResource::collection($promotedEvents)
        ]);
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->with(['category', 'venue', 'organizer', 'ticketTypes'])->firstOrFail();
        return new EventResource($event);
    }
}
