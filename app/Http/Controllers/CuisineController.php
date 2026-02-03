<?php

namespace App\Http\Controllers;

use App\Models\Cuisine;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class CuisineController extends Controller
{
    // Get all cuisines
    public function index(Request $request)
    {
        $query = Cuisine::withCount('vendors');

        // Apply search filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $cuisines = $query->latest()->paginate($request->per_page ?? 15);

        

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cuisines
            ]);
        }

        return view('dashboard.cuisines.index', compact('cuisines'));
    }

    /**
     * Dashboard: show create form
     */
    public function createCuisine()
    {
        return view('dashboard.cuisines.create');
    }

    /**
     * Dashboard: store cuisine
     */
    public function storeCuisine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cuisines',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload to public/uploads/cuisines
        if ($request->hasFile('image')) {
            $dir = public_path('uploads/cuisines');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $validated['image'] = 'cuisines/' . $filename;
        }

        Cuisine::create($validated);

        return redirect()->route('dashboard.cuisines.index')
            ->with('success', 'Cuisine created successfully.');
    }

    /**
     * Dashboard: edit form
     */
    public function editCuisine(Cuisine $cuisine)
    {
        return view('dashboard.cuisines.edit', compact('cuisine'));
    }

    /**
     * Dashboard: update cuisine
     */
    public function updateCuisine(Request $request, Cuisine $cuisine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cuisines,name,' . $cuisine->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->has('remove_image') && $request->boolean('remove_image')) {
            if ($cuisine->image && File::exists(public_path('uploads/' . $cuisine->image))) {
                File::delete(public_path('uploads/' . $cuisine->image));
            }
            $validated['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($cuisine->image && File::exists(public_path('uploads/' . $cuisine->image))) {
                File::delete(public_path('uploads/' . $cuisine->image));
            }
            $dir = public_path('uploads/cuisines');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $validated['image'] = 'cuisines/' . $filename;
        }

        $cuisine->update($validated);

        return redirect()->route('dashboard.cuisines.index')
            ->with('success', 'Cuisine updated successfully.');
    }

    /**
     * Dashboard: delete cuisine
     */
    public function destroyCuisine(Cuisine $cuisine)
    {
        if ($cuisine->vendors()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete cuisine with associated vendors.');
        }

        if ($cuisine->image && File::exists(public_path('uploads/' . $cuisine->image))) {
            File::delete(public_path('uploads/' . $cuisine->image));
        }

        $cuisine->delete();

        return redirect()->route('dashboard.cuisines.index')
            ->with('success', 'Cuisine deleted successfully.');
    }

    // Create new cuisine (admin only)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can create cuisines'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:cuisines,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cuisineData = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $cuisineData['image'] = $request->file('image')->store('cuisines', 'public');
        }

        $cuisine = Cuisine::create($cuisineData);

        return response()->json([
            'success' => true,
            'message' => 'Cuisine created successfully',
            'data' => $cuisine
        ], 201);
    }

    // Get cuisine details
    public function show($id)
    {
        $cuisine = Cuisine::withCount('vendors')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $cuisine
        ]);
    }

    // Update cuisine (admin only)
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can update cuisines'
            ], 403);
        }

        $cuisine = Cuisine::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:cuisines,name,' . $id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cuisineData = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($cuisine->image) {
                Storage::disk('public')->delete($cuisine->image);
            }
            $cuisineData['image'] = $request->file('image')->store('cuisines', 'public');
        }

        $cuisine->update($cuisineData);

        return response()->json([
            'success' => true,
            'message' => 'Cuisine updated successfully',
            'data' => $cuisine
        ]);
    }

    // Delete cuisine (admin only)
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can delete cuisines'
            ], 403);
        }

        $cuisine = Cuisine::findOrFail($id);

        // Check if cuisine has vendors
        if ($cuisine->vendors()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete cuisine with associated vendors'
            ], 422);
        }

        // Delete image
        if ($cuisine->image) {
            Storage::disk('public')->delete($cuisine->image);
        }

        $cuisine->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cuisine deleted successfully'
        ]);
    }

    /**
     * Dashboard: show cuisine details
     */
    public function showCuisine(Cuisine $cuisine)
    {
        $cuisine->loadCount('vendors');
        return view('dashboard.cuisines.show', compact('cuisine'));
    }

    // Get vendors by cuisine
    public function vendors($id, Request $request)
    {
        $cuisine = Cuisine::findOrFail($id);

        $query = Vendor::where('cuisine', $cuisine->name)
            ->where('verified', true)
            ->with(['user']);

        // Apply filters
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('featured')) {
            $query->where('featured', $request->featured);
        }

        $vendors = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => [
                'cuisine' => $cuisine,
                'vendors' => $vendors
            ]
        ]);
    }

    // Get products by cuisine
    public function products($id, Request $request)
    {
        $cuisine = Cuisine::findOrFail($id);

        $query = Product::where('cuisine', $cuisine->name)
            ->where('status', 'active')
            ->with(['vendor', 'category']);

        // Apply filters
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => [
                'cuisine' => $cuisine,
                'products' => $products
            ]
        ]);
    }
}
