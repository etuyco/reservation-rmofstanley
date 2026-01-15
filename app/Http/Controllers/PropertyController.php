<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('is_active', true)->get();
        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    /**
     * Display a listing of all properties for admin management
     */
    public function adminIndex()
    {
        $properties = Property::latest()->get();
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property (Admin only)
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created property (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Park,Conference Room,Equipment',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active');

        Property::create($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property created successfully.');
    }

    /**
     * Show the form for editing a property (Admin only)
     */
    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified property (Admin only)
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Park,Conference Room,Equipment',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active');

        $property->update($validated);

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property updated successfully.');
    }

    /**
     * Remove the specified property (Admin only)
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
