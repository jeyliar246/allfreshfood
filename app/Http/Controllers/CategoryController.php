<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Get all categories
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Apply search filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Dashboard: list categories
     */
    public function categories()
    {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Dashboard: show create form
     */
    public function createCategory()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Dashboard: store category
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);


        if ($request->hasFile('image')) {
            $dir = public_path('uploads/categories');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            // Store relative path under uploads (e.g., products/filename.jpg)
            $validated['image'] = 'categories/' . $filename;
        }

        Category::create($validated);

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Dashboard: show category
     */
    public function showCategory(Category $category)
    {
        $category->loadCount('products');
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Dashboard: edit form
     */
    public function editCategory(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Dashboard: update category
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
        ]);

        // if ($request->has('remove_image') && $request->remove_image) {
        //     if ($category->image) {
        //         Storage::disk('public')->delete($category->image);
        //         $validated['image'] = null;
        //     }
        // } elseif ($request->hasFile('image')) {
        //     if ($category->image) {
        //         Storage::disk('public')->delete($category->image);
        //     }
        //     $validated['image'] = $request->file('image')->store('categories', 'public');
        // } else {
        //     unset($validated['image']);
        // }

        if ($request->has('remove_image') && $request->remove_image) {
            if ($category->image) {
                // Storage::disk('public')->delete($category->image);
                File::delete(public_path('uploads/' . $category->image));
                $validated['image'] = null;
            }
        }
        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path('category/' . $category->image))) {
                File::delete(public_path('uploads/' . $category->image));
            }
            // Store directly under public/uploads/products
            $dir = public_path('uploads/category');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $productData['image'] = 'category/' . $filename; // e.g. products/filename.jpg
        }



        $category->update($validated);

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Dashboard: delete category
     */
    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated products.');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    // Create new category (admin only)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can create categories'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
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

        $categoryData = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/categories');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $categoryData['image'] = 'categories/'.$filename;
        }

        $category = Category::create($categoryData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    // Get category details
    public function show($id)
    {
        $category = Category::with(['products' => function($query) {
            $query->where('status', 'active');
        }])->withCount('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    // Update category (admin only)
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can update categories'
            ], 403);
        }

        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
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

        $categoryData = $request->only(['name', 'description']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && File::exists(public_path('uploads/' . $category->image))) {
                File::delete(public_path('uploads/' . $category->image));
            }
            $dir = public_path('uploads/categories');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);
            $filename = uniqid().'_'.preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $categoryData['image'] = 'categories/'.$filename;
        }

        $category->update($categoryData);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    // Delete category (admin only)
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only administrators can delete categories'
            ], 403);
        }

        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with associated products'
            ], 422);
        }

        // Delete image
        if ($category->image && File::exists(public_path('uploads/' . $category->image))) {
            File::delete(public_path('uploads/' . $category->image));
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    // Get products by category
    public function products($id, Request $request)
    {
        $category = Category::findOrFail($id);

        $query = $category->products()->with(['vendor', 'category'])
            ->where('status', 'active');

        // Apply filters
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'products' => $products
            ]
        ]);
    }
}
