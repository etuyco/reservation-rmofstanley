<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('category')->where('is_active', true);

        // Search by name, location, or description
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type (now using category)
        if ($request->filled('type')) {
            $categoryName = $request->get('type');
            $query->whereHas('category', function ($q) use ($categoryName) {
                $q->where('name', $categoryName);
            });
        }

        // Filter by minimum capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->get('capacity'));
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $minPrice = $request->get('min_price');
            if ($minPrice == 0) {
                // Include free properties (price = 0 or null) and paid properties >= min_price
                $query->where(function ($q) use ($minPrice) {
                    $q->where('price_per_hour', '>=', $minPrice)
                      ->orWhereNull('price_per_hour');
                });
            } else {
                $query->where('price_per_hour', '>=', $minPrice);
            }
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $request->get('max_price'));
        }

        // Filter by availability status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'available') {
                // This would require checking current bookings/reservations
                // For now, we'll use the is_active flag as a proxy
                $query->where('is_active', true);
            }
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->get('location') . '%');
        }

        // Sort by price, name, capacity, or created date
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if (in_array($sortBy, ['name', 'price_per_hour', 'capacity', 'created_at'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Add secondary sort by name for consistency
        if ($sortBy !== 'name') {
            $query->orderBy('name', 'asc');
        }

        $properties = $query->paginate(12);
        
        // Preserve query parameters in pagination links
        $properties->appends($request->query());

        // Get categories for filter dropdown
        $categories = Category::active()->orderBy('name')->get();
        $propertyTypes = $categories->pluck('name')->toArray();

        // Get unique locations for filter dropdown
        $locations = Property::where('is_active', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values()
            ->toArray();

        // Get statistics for display
        $totalProperties = Property::where('is_active', true)->count();
        $availableNow = Property::where('is_active', true)->get()->filter(function ($property) {
            return $property->current_status === 'available';
        })->count();

        // Get price range for filter suggestions
        $priceRange = Property::where('is_active', true)
            ->whereNotNull('price_per_hour')
            ->selectRaw('MIN(price_per_hour) as min_price, MAX(price_per_hour) as max_price')
            ->first();

        // Always use properties.index view (now serves as homepage too)
        return view('properties.index', compact(
            'properties', 
            'propertyTypes',
            'categories', 
            'locations',
            'totalProperties', 
            'availableNow',
            'priceRange'
        ));
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
        $properties = Property::with('category')->latest()->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.properties.index', compact('properties', 'categories'));
    }

    /**
     * Show the form for creating a new property (Admin only)
     */
    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('properties.create', compact('categories'));
    }

    /**
     * Store a newly created property (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'max_daily_booking_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        // Handle image file upload
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('properties', 'public');
            $validated['image_path'] = $imagePath;
        }

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
        $categories = Category::active()->orderBy('name')->get();
        return view('properties.edit', compact('property', 'categories'));
    }

    /**
     * Update the specified property (Admin only)
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'max_daily_booking_days' => 'nullable|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        // Handle image file upload
        if ($request->hasFile('image_file')) {
            // Delete old image if it exists
            if ($property->image_path && \Storage::disk('public')->exists($property->image_path)) {
                \Storage::disk('public')->delete($property->image_path);
            }
            
            $imagePath = $request->file('image_file')->store('properties', 'public');
            $validated['image_path'] = $imagePath;
        }

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
