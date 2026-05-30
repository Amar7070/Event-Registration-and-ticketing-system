<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::withCount('events')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        EventCategory::create($validated);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, EventCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name,' . $category->id,
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(EventCategory $category)
    {
        if ($category->events()->exists()) {
            return back()->with('error', 'Cannot delete category with associated events.');
        }

        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
