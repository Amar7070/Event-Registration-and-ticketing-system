<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function stats(Request $request)
    {
        $organizer = $request->user();
        $eventIds = $organizer->organizedEvents()->pluck('id');

        $confirmedTickets = Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'confirmed')
            ->sum('quantity');

        $checkedInTickets = Ticket::whereIn('event_id', $eventIds)
            ->where('status', 'confirmed')
            ->whereNotNull('checked_in_at')
            ->sum('quantity');

        $revenue = Payment::whereIn('event_id', $eventIds)
            ->where('status', 'completed')
            ->sum('amount');

        return response()->json([
            'events_created' => $eventIds->count(),
            'tickets_sold' => $confirmedTickets,
            'total_attendance' => $checkedInTickets,
            'total_revenue' => $revenue,
        ]);
    }

    public function attendees(Event $event)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && $event->organizer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attendees = Ticket::with(['user', 'ticketType'])
            ->where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->get()
            ->map(function ($ticket) {
                return [
                    'ticket_reference' => $ticket->booking_reference,
                    'attendee_name' => $ticket->user->name,
                    'attendee_email' => $ticket->user->email,
                    'ticket_type' => $ticket->ticketType->name,
                    'quantity' => $ticket->quantity,
                    'checked_in_at' => optional($ticket->checked_in_at)->toIso8601String(),
                ];
            });

        return response()->json(['data' => $attendees]);
    }

    public function globalAttendees(Request $request)
    {
        $organizer = $request->user();
        $eventIds = $organizer->organizedEvents()->pluck('id');

        $attendees = Ticket::with(['user', 'ticketType', 'event'])
            ->whereIn('event_id', $eventIds)
            ->where('status', 'confirmed')
            ->latest()
            ->get()
            ->map(function ($ticket) {
                return [
                    'ticket_reference' => $ticket->booking_reference,
                    'attendee_name' => $ticket->user->name,
                    'attendee_email' => $ticket->user->email,
                    'event_name' => $ticket->event->title,
                    'ticket_type' => $ticket->ticketType->name,
                    'quantity' => $ticket->quantity,
                    'checked_in_at' => optional($ticket->checked_in_at)->toIso8601String(),
                ];
            });

        return response()->json(['data' => $attendees]);
    }

    public function couponsIndex(Request $request)
    {
        $organizer = $request->user();
        $coupons = \App\Models\Coupon::with('event')
            ->where('organizer_id', $organizer->id)
            ->latest()
            ->get()
            ->map(function ($coupon) {
                return [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => (float)$coupon->value,
                    'event_name' => $coupon->event ? $coupon->event->title : 'All Events',
                    'valid_from' => $coupon->valid_from->toIso8601String(),
                    'valid_until' => $coupon->valid_until->toIso8601String(),
                    'is_active' => $coupon->is_active,
                    'usage_count' => $coupon->used_count,
                    'usage_limit' => $coupon->usage_limit
                ];
            });

        return response()->json(['data' => $coupons]);
    }

    public function couponsStore(Request $request)
    {
        $organizer = $request->user();
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'event_id' => 'nullable|exists:events,id',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        if ($request->filled('event_id')) {
            $event = Event::findOrFail($request->event_id);
            if ($event->organizer_id !== $organizer->id) {
                return response()->json(['message' => 'Unauthorized for this event.'], 403);
            }
        }

        $coupon = \App\Models\Coupon::create([
            'organizer_id' => $organizer->id,
            'event_id' => $request->event_id,
            'code' => $validated['code'],
            'type' => $validated['type'],
            'value' => $validated['value'],
            'valid_from' => $validated['valid_from'],
            'valid_until' => $validated['valid_until'],
            'usage_limit' => $request->usage_limit,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Coupon created successfully', 'data' => $coupon], 201);
    }

    public function couponsUpdate(Request $request, \App\Models\Coupon $coupon)
    {
        $organizer = $request->user();
        if ($coupon->organizer_id !== $organizer->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'is_active' => 'sometimes|boolean',
            'usage_limit' => 'sometimes|nullable|integer|min:1',
            'valid_until' => 'sometimes|date',
        ]);

        $coupon->update($validated);

        return response()->json(['message' => 'Coupon updated', 'data' => $coupon]);
    }

    public function couponsDestroy(Request $request, \App\Models\Coupon $coupon)
    {
        $organizer = $request->user();
        if ($coupon->organizer_id !== $organizer->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully']);
    }

    public function copyrightReports(Request $request)
    {
        $organizer = $request->user();
        $eventIds = $organizer->organizedEvents()->pluck('id');

        $receivedReports = \App\Models\CopyrightReport::with(['event', 'user'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'event_id' => $report->event_id,
                    'event_name' => $report->event->title,
                    'subject' => $report->subject,
                    'description' => $report->description,
                    'evidence_url' => $report->evidence_url,
                    'status' => $report->status,
                    'reporter_name' => $report->user->name,
                    'created_at' => $report->created_at->diffForHumans(),
                ];
            });

        $filedReports = \App\Models\CopyrightReport::with(['event.organizer', 'user'])
            ->where('user_id', $organizer->id)
            ->latest()
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->id,
                    'event_id' => $report->event_id,
                    'event_name' => $report->event ? $report->event->title : 'Unknown Event',
                    'reported_organizer' => $report->event && $report->event->organizer ? $report->event->organizer->name : 'Unknown Organizer',
                    'subject' => $report->subject,
                    'description' => $report->description,
                    'evidence_url' => $report->evidence_url,
                    'status' => $report->status,
                    'reporter_name' => $report->user->name,
                    'created_at' => $report->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'data' => [
                'received' => $receivedReports,
                'filed' => $filedReports,
            ]
        ]);
    }
}
