<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventPromotionPlan;
use Illuminate\Http\Request;

class PromotionPlanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
        ]);

        EventPromotionPlan::create($validated);

        return back()->with('success', 'Showcase advertisement plan created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $plan = EventPromotionPlan::findOrFail($id);
        
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        
        $plan->update($validated);

        return back()->with('success', 'Showcase plan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = EventPromotionPlan::findOrFail($id);
        $plan->delete();

        return back()->with('success', 'Showcase plan deleted successfully!');
    }
}
