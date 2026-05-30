<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Event $event)
    {
        $this->authorize('update', $event);
        $coupons = Coupon::where('event_id', $event->id)
                         ->orWhere(function ($query) use ($event) {
                             $query->whereNull('event_id')
                                   ->where('organizer_id', $event->organizer_id);
                         })->get();
                         
        return view('organizer.coupons.index', compact('event', 'coupons'));
    }

    public function create(Event $event)
    {
        $this->authorize('update', $event);
        return view('organizer.coupons.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'apply_to_all_events' => 'boolean'
        ]);

        $coupon = new Coupon($validated);
        $coupon->organizer_id = auth()->id();
        
        if (empty($request->apply_to_all_events)) {
            $coupon->event_id = $event->id;
        }

        $coupon->save();

        return redirect()->route('organizer.events.coupons.index', $event)
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Event $event, Coupon $coupon)
    {
        $this->authorize('update', $event);
        if ($coupon->event_id !== $event->id && $coupon->organizer_id !== auth()->id()) {
            abort(404);
        }

        return view('organizer.coupons.edit', compact('event', 'coupon'));
    }

    public function update(Request $request, Event $event, Coupon $coupon)
    {
        $this->authorize('update', $event);
        if ($coupon->event_id !== $event->id && $coupon->organizer_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => 'required|in:percentage,flat',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'apply_to_all_events' => 'boolean'
        ]);

        $coupon->fill($validated);
        if (!empty($request->apply_to_all_events)) {
            $coupon->event_id = null;
        } else {
            $coupon->event_id = $event->id;
        }
        $coupon->save();

        return redirect()->route('organizer.events.coupons.index', $event)
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Event $event, Coupon $coupon)
    {
        $this->authorize('update', $event);
        if ($coupon->event_id !== $event->id && $coupon->organizer_id !== auth()->id()) {
            abort(404);
        }

        $coupon->delete();

        return redirect()->route('organizer.events.coupons.index', $event)
            ->with('success', 'Coupon deleted successfully.');
    }
}
