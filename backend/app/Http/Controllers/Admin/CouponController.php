<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of all ecosystem coupons.
     */
    public function index()
    {
        $coupons = Coupon::with(['event', 'organizer'])
            ->latest()
            ->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        // Check if this is a simple toggle operation
        if ($request->has('toggle_active')) {
            $coupon->update([
                'is_active' => !$coupon->is_active
            ]);
            return redirect()->route('admin.coupons.index')
                ->with('success', 'Coupon active status toggled successfully.');
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
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon parameters modified successfully.');
    }

    /**
     * Purge the specified coupon from the database.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon purged from the ecosystem.');
    }
}
