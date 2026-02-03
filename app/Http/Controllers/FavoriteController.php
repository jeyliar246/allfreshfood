<?php

namespace App\Http\Controllers;

use App\Models\UserFavoriteVendor;
use App\Models\UserFavoriteProduct;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Toggle vendor favorite
    public function toggleVendorFavorite($vendorId)
    {
        $user = Auth::user();
        $vendor = Vendor::findOrFail($vendorId);

        $favorite = UserFavoriteVendor::where('user_id', $user->id)
            ->where('vendor_id', $vendorId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Vendor removed from favorites';
            $isFavorite = false;
        } else {
            UserFavoriteVendor::create([
                'user_id' => $user->id,
                'vendor_id' => $vendorId
            ]);
            $message = 'Vendor added to favorites';
            $isFavorite = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite
        ]);
    }

    // Toggle product favorite
    public function toggleProductFavorite($productId)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

        $favorite = UserFavoriteProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Product removed from favorites';
            $isFavorite = false;
        } else {
            UserFavoriteProduct::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $message = 'Product added to favorites';
            $isFavorite = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorite' => $isFavorite
        ]);
    }

    // Get user's favorite vendors
    public function getFavoriteVendors(Request $request)
    {
        $user = Auth::user();

        $favorites = UserFavoriteVendor::with(['vendor' => function($query) {
            $query->withCount('products');
            $query->withAvg('reviews', 'rating');
        }])
        ->where('user_id', $user->id)
        ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    // Get user's favorite products
    public function getFavoriteProducts(Request $request)
    {
        $user = Auth::user();

        $favorites = UserFavoriteProduct::with(['product' => function($query) {
            $query->with('vendor');
            $query->withAvg('reviews', 'rating');
        }])
        ->where('user_id', $user->id)
        ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    // Check if vendor is favorite
    public function checkVendorFavorite($vendorId)
    {
        $user = Auth::user();

        $isFavorite = UserFavoriteVendor::where('user_id', $user->id)
            ->where('vendor_id', $vendorId)
            ->exists();

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite
        ]);
    }

    // Check if product is favorite
    public function checkProductFavorite($productId)
    {
        $user = Auth::user();

        $isFavorite = UserFavoriteProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'is_favorite' => $isFavorite
        ]);
    }
}
