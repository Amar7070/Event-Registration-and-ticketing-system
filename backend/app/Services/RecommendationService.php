<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;

class RecommendationService
{
    /**
     * Get recommended events for a user based on their past bookings and categories.
     */
    public function getRecommendationsForUser(User $user, int $limit = 5)
    {
        // Simple recommendation logic:
        // 1. Get categories the user has booked tickets for
        // 2. Recommend upcoming events in those same categories
        
        $bookedCategoryIds = $user->tickets()
            ->with('event')
            ->get()
            ->pluck('event.category_id')
            ->filter()
            ->unique()
            ->toArray();

        if (empty($bookedCategoryIds)) {
            // Fallback: Return trending/most booked upcoming events
            return Event::where('start_date', '>', now())
                ->where('visibility', 'public')
                ->orderBy('registered_count', 'desc')
                ->take($limit)
                ->get();
        }

        return Event::whereIn('category_id', $bookedCategoryIds)
            ->where('start_date', '>', now())
            ->where('visibility', 'public')
            // Exclude events they already booked
            ->whereNotIn('id', function($query) use ($user) {
                $query->select('event_id')
                      ->from('tickets')
                      ->where('user_id', $user->id);
            })
            ->orderBy('registered_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get similar events based on category, excluding the current event.
     */
    public function getSimilarEvents(Event $event, int $limit = 3)
    {
        $events = Event::where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->where('start_date', '>', now())
            ->where('visibility', 'public')
            ->orderBy('registered_count', 'desc')
            ->take($limit)
            ->get();

        if ($events->count() < $limit) {
            $excludeIds = $events->pluck('id')->push($event->id)->toArray();
            $fallbackEvents = Event::whereNotIn('id', $excludeIds)
                ->where('start_date', '>', now())
                ->where('visibility', 'public')
                ->orderBy('registered_count', 'desc')
                ->take($limit - $events->count())
                ->get();
            $events = $events->concat($fallbackEvents);
        }

        return $events;
    }
}
