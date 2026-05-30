<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\Refund;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get detailed metrics for a specific event.
     */
    public function getEventMetrics(Event $event): array
    {
        $revenue = Payment::where('event_id', $event->id)
            ->where('status', 'completed')
            ->sum('organizer_earnings');

        $ticketsSold = Ticket::where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->sum('quantity');

        $attendanceCount = $event->attendance_count ?? Ticket::where('event_id', $event->id)
            ->whereNotNull('checked_in_at')
            ->sum('quantity');

        $refundsTotal = Refund::whereHas('ticket', fn($q) => $q->where('event_id', $event->id))
            ->where('status', 'processed')
            ->sum('amount');

        // Daily Sales (Last 30 Days)
        $dailySales = Ticket::where('event_id', $event->id)
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(quantity) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue by Ticket Type
        $revenueByType = TicketType::where('event_id', $event->id)
            ->withSum(['tickets' => function($q) {
                $q->where('status', 'confirmed');
            }], 'total_amount')
            ->get();

        return [
            'total_revenue' => $revenue,
            'tickets_sold' => $ticketsSold,
            'attendance_rate' => $ticketsSold > 0 ? ($attendanceCount / $ticketsSold) * 100 : 0,
            'refunds_total' => $refundsTotal,
            'daily_sales' => $dailySales,
            'revenue_by_type' => $revenueByType,
            'capacity_usage' => $event->total_capacity > 0 ? ($ticketsSold / $event->total_capacity) * 100 : 0,
        ];
    }

    /**
     * Get platform-wide metrics for admins.
     */
    public function getPlatformMetrics(): array
    {
        return [
            'total_users' => \App\Models\User::count(),
            'total_events' => Event::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'total_platform_fees' => Payment::where('status', 'completed')->sum('platform_fee'),
            'total_tickets' => Ticket::where('status', 'confirmed')->sum('quantity'),
            'category_distribution' => \App\Models\EventCategory::withCount('events')->get(),
            'top_organizers' => \App\Models\User::role('organizer')
                ->withSum(['organizedEvents as revenue' => function($q) {
                    $q->join('payments', 'events.id', '=', 'payments.event_id')
                      ->where('payments.status', 'completed');
                }], 'payments.organizer_earnings')
                ->orderByDesc('revenue')
                ->limit(10)
                ->get(),
        ];
    }
}
