<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published()->upcoming()->where('is_restricted', false)->with(['category', 'venue']);

        // Filtering
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
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

        // Search (using simple LIKE for now, can move to Scout later)
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->latest()->paginate(12);
        $categories = EventCategory::where('is_active', true)->get();

        // Fetch active, currently valid, within usage limit coupons
        $activeCoupons = \App\Models\Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>', now())
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->with(['event', 'organizer'])
            ->latest()
            ->get();

        return view('events.index', compact('events', 'categories', 'activeCoupons'));
    }

    public function show($slug, \App\Services\RecommendationService $recommendationService)
    {
        $event = Event::where('slug', $slug)
            ->with(['category', 'venue', 'sessions.speaker', 'speakers', 'sponsors'])
            ->firstOrFail();

        if ($event->is_restricted) {
            abort(403, 'This experience has been suspended due to copyright or regulatory restrictions: ' . ($event->restriction_reason ?? 'Illegal Content Policy'));
        }

        $recommendedEvents = $recommendationService->getSimilarEvents($event);

        return view('events.show', compact('event', 'recommendedEvents'));
    }
}
