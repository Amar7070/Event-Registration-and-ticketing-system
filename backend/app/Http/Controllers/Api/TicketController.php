<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()->tickets()->with(['event', 'ticketType'])->latest()->paginate(15);

        return TicketResource::collection($tickets);
    }

    public function waitlists(Request $request)
    {
        $waitlists = $request->user()->waitlists()->with(['event', 'ticketType'])->latest()->get();

        return response()->json([
            'data' => $waitlists->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'event' => [
                        'title' => $entry->event->title ?? null,
                        'starts_at' => optional($entry->event->start_date)->toIso8601String(),
                    ],
                    'ticket_type' => $entry->ticketType->name ?? null,
                    'status' => $entry->status,
                    'position' => $entry->position,
                    'notified_at' => optional($entry->notified_at)->toIso8601String(),
                    'expires_at' => optional($entry->expires_at)->toIso8601String(),
                ];
            }),
        ]);
    }
}
