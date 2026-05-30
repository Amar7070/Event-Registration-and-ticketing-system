<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Event $event)
    {
        $reviews = $event->reviews()->with('user:id,name')->latest()->get();
        return response()->json($reviews);
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        // Check if user has a ticket for this event
        $hasTicket = $event->tickets()->where('user_id', $user->id)->exists();
        if (!$hasTicket) {
            return response()->json(['message' => 'You must have a ticket for this event to leave a review.'], 403);
        }

        // Check if already reviewed
        $existingReview = $event->reviews()->where('user_id', $user->id)->first();
        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this event.'], 403);
        }

        $review = $event->reviews()->create([
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Review submitted successfully', 'review' => $review->load('user:id,name')], 201);
    }
}
