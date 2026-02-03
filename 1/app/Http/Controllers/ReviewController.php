<?php

namespace App\Http\Controllers;

use App\Models\VendorReview;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function submitVendorReview(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $order = Order::findOrFail($request->order_id);

        // Verify user owns the order
        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if review already exists
        $existingReview = VendorReview::where('user_id', $user->id)
            ->where('order_id', $request->order_id)
            ->first();

        if ($existingReview) {
            return response()->json(['success' => false, 'message' => 'Review already submitted'], 400);
        }

        $review = VendorReview::create([
            'user_id' => $user->id,
            'vendor_id' => $request->vendor_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review
        ]);
    }

    public function submitProductReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        $user = Auth::user();
        $order = Order::findOrFail($request->order_id);

        // Verify user owns the order and order contains the product
        if ($order->user_id !== $user->id || !$order->items->contains('product_id', $request->product_id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $review = ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully',
            'data' => $review
        ]);
    }
}
