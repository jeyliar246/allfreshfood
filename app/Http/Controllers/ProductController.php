<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MarkUp;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Get all products with filters
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category']);

        if(Auth::user()->role === 'vendor') {
            $vendor = Vendor::where('user_id', Auth::id())->first();
            $vendor_id = $vendor->id;
            // $query = Product::with(['vendor', 'category'])->where('vendor_id',  $vendor_id);
        } else {
            $vendor_id =  $request->vendor_id;
            // $query = Product::with(['vendor', 'category']);
        }

        // Apply filters
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $vendor_id);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('cuisine')) {
            $query->where('cuisine', $request->cuisine);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->where('status', 'active')->latest()->paginate($request->per_page ?? 20);

        

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }
    }

     /**
     * Display a listing of the products.
     */
    public function products(Request $request)
    {
        if(Auth::user()->role === 'vendor') {
            $vendor = Vendor::where('user_id', Auth::id())->first();
            $vendor_id = $vendor->id;
        } else {
            $vendor_id =  $request->vendor_id;
        }
        
        $query = Product::with(['vendor', 'category']);

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Apply status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Apply vendor filter
        if ($request->has('vendor') && $request->vendor != '') {
            $query->where('vendor_id', $request->vendor);
        }

        if(Auth::user()->role === 'vendor') {
            $products = $query->where('vendor_id', $vendor_id)->latest()->paginate(15);
        }else{
            $products = $query->latest()->paginate(20);
        }
        $categories = Category::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();

        return view('dashboard.products.index', compact('products', 'categories', 'vendors'));
    }

    // Create new product (vendor only)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can create products'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0',
            'cuisine' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'halal' => 'nullable|boolean',
            'vegan' => 'nullable|boolean',
            'gluten_free' => 'nullable|boolean',
            'organic' => 'nullable|boolean',
            'fair_trade' => 'nullable|boolean',
            'non_GMO' => 'nullable|boolean',
        ]);

        $productData = $request->only([
            'name', 'category_id', 'price', 'original_price',
            'description', 'stock', 'cuisine', 'halal', 'vegan', 'gluten_free', 'organic', 'fair_trade', 'non_GMO', 'category'
        ]);

        $productData['vendor_id'] = $vendor->id;
        $productData['status'] = 'active';

        if ($request->hasFile('image')) {
            $dir = public_path('uploads/products');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $productData['image'] = 'products/' . $filename;
        }

        $product = Product::create($productData);
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load('vendor', 'category')
            ], 201);
        }

        notyf()->success('Product created successfully.');
        return redirect()->back();
    }


     /**
     * Show the form for creating a new product.
     */
    public function createProduct()
    {
        $categories = Category::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        return view('dashboard.products.create', compact('categories', 'vendors'));
    }

    // Get product details
    public function show($id)
    {
        $product = Product::with(['vendor', 'category'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }


     /**
     * Store a newly created product in storage.
     */
    public function storeProduct(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'vendor_id' => 'required|exists:vendors,id',
                'price' => 'required|numeric|min:0',
                'original_price' => 'nullable|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
                'status' => 'required|in:active,inactive',
                'cuisine' => 'nullable|string|max:100',
                'halal' => 'nullable|boolean',
                'vegan' => 'nullable|boolean',
                'gluten_free' => 'nullable|boolean',
                'organic' => 'nullable|boolean',
                'fair_trade' => 'nullable|boolean',
                'non_GMO' => 'nullable|boolean',
                'deal' => 'nullable|string|in:active,inactive',
                'discount' => 'nullable|numeric|min:0|max:100',
            ]);

            $category = Category::where('id', $validated['category_id'])->first();
            $validated['category'] = $category->name;

            $markUp = MarkUp::first();
            $markupPercentage = $markUp ? $markUp->markup_percentage : 10;
       

            $percentPrice = ($validated['price'] * $markupPercentage / 100);
            $perPrice =  $validated['price'] + $percentPrice;
            
            $validated['pprice'] = round($perPrice, 2);


            // Handle discount calculation
            // if (isset($validated['discount']) && $validated['discount'] > 0) {
            //     $discountAmount = ($validated['price'] * $validated['discount']) / 100;
            //     $validated['final_price'] = $validated['price'] - $discountAmount;
            // } else {
            //     $validated['final_price'] = $validated['price'];
            //     $validated['discount'] = 0;
            // }

            // Handle image upload to public/uploads/products
            if ($request->hasFile('image')) {
                $dir = public_path('uploads/products');
                if (!File::exists($dir)) {
                    File::makeDirectory($dir, 0755, true);
                }
                $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
                $request->file('image')->move($dir, $filename);
                // Store relative path under uploads (e.g., products/filename.jpg)
                $validated['image'] = 'products/' . $filename;
            }

            // Add created_by user
            $validated['created_by'] = Auth::id();

            // Create the product
            $product = Product::create($validated);

            notyf()->success('Product created successfully.');
            return redirect()->route('dashboard.products.show', $product);
                
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product. Please try again.');
        }
    }

    /**
     * Display the specified product.
     */
    public function showProduct(Product $product)
    {
        $product->load(['category', 'vendor']);
        return view('dashboard.products.show', compact('product'));
    }


    // Update product (vendor only)
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can update products'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        if ($product->vendor_id !== $vendor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this product'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'category_id' => 'sometimes|exists:categories,id',
            'price' => 'sometimes|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'stock' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:active,inactive',
            'cuisine' => 'nullable|string|max:100',
        ]);

        $productData = $request->only([
            'name', 'category_id', 'price', 'original_price',
            'description', 'stock', 'status', 'cuisine'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path('uploads/' . $product->image))) {
                File::delete(public_path('uploads/' . $product->image));
            }
            // Store directly under public/uploads/products
            $dir = public_path('uploads/products');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $productData['image'] = 'products/' . $filename; // e.g. products/filename.jpg
        }

        $product->update($productData);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('vendor', 'category')
        ]);
    }



    /**
     * Update the specified product in storage.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
            'cuisine' => 'nullable|string|max:100',
            'remove_image' => 'nullable|boolean',
            'halal' => 'nullable|boolean',
            'vegan' => 'nullable|boolean',
            'gluten_free' => 'nullable|boolean',
            'organic' => 'nullable|boolean',
            'fair_trade' => 'nullable|boolean',
            'non_GMO' => 'nullable|boolean',
            'deal' => 'nullable|string|in:active,inactive',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($product->image) {
                if (File::exists(public_path('uploads/' . $product->image))) {
                    File::delete(public_path('uploads/' . $product->image));
                }
                $validated['image'] = null;
            }
        }
        // Handle new image upload
        elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                if (File::exists(public_path('uploads/' . $product->image))) {
                    File::delete(public_path('uploads/' . $product->image));
                }
            }
            $dir = public_path('uploads/products');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = uniqid() . '_' . preg_replace('/\s+/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($dir, $filename);
            $validated['image'] = 'products/' . $filename;
        } else {
            unset($validated['image']); // Don't update the image if not provided
        }

        $category = Category::where('id', $validated['category_id'])->first();
        $validated['category'] = $category->name;

        

        $markUp = MarkUp::first();
        $markupPercentage = $markUp ? $markUp->markup_percentage : 10;
        $percentPrice = ($validated['price'] * $markupPercentage / 100);
        
        $perPrice =  $validated['price'] + $percentPrice;
        
        $validated['pprice'] = round($perPrice, 2);

        $product->update($validated);

        notyf()->success('Product updated successfully.');
        return redirect()->route('dashboard.products.index', $product)
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroyProduct(Product $product)
    {
        // Delete the product image if exists
        if ($product->image) {
            if (File::exists(public_path('uploads/' . $product->image))) {
                File::delete(public_path('uploads/' . $product->image));
            }
        }

        $product->delete();

        notyf()->success('Product deleted successfully.');
        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function editProduct(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();
        
        // Load the related category and vendor to ensure they're available
        $product->load(['category', 'vendor']);
        
        return view('dashboard.products.edit', compact('product', 'categories', 'vendors'));
    }

    // Delete product (vendor only)
    public function destroy($id, Request $request)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can delete products'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        if ($product->vendor_id !== $vendor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this product'
            ], 403);
        }

        // Delete image
        if ($product->image && File::exists(public_path('uploads/' . $product->image))) {
            File::delete(public_path('uploads/' . $product->image));
        }

        $product->delete();

        

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        }

        notyf()->success('Product deleted successfully.');
        return redirect()->back();
    }
}
