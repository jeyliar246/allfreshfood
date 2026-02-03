<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    // Get order items for a specific order
    public function index($orderId, Request $request)
    {
        $order = Order::findOrFail($orderId);
        $user = Auth::user();

        // Authorization
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order items'
            ], 403);
        }

        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor || $order->vendor_id !== $vendor->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to order items'
                ], 403);
            }
        }

        $orderItems = OrderItem::with(['product', 'order'])
            ->where('order_id', $orderId)
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $orderItems
        ]);
    }

    // Get specific order item details
    public function show($orderId, $itemId)
    {
        $orderItem = OrderItem::with(['product', 'order'])
            ->where('order_id', $orderId)
            ->where('id', $itemId)
            ->firstOrFail();

        $user = Auth::user();
        $order = $orderItem->order;

        // Authorization
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to order item'
            ], 403);
        }

        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor || $order->vendor_id !== $vendor->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to order item'
                ], 403);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $orderItem
        ]);
    }

    // Update order item (vendor only - for special cases like replacements)
    public function update(Request $request, $orderId, $itemId)
    {
        $orderItem = OrderItem::with(['order'])
            ->where('order_id', $orderId)
            ->where('id', $itemId)
            ->firstOrFail();

        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can update order items'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->first();
        if (!$vendor || $orderItem->order->vendor_id !== $vendor->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this order item'
            ], 403);
        }

        // Only allow updates if order is in early stages
        if (!in_array($orderItem->order->status, ['pending', 'confirmed', 'preparing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order item cannot be updated at this stage'
            ], 422);
        }

        $request->validate([
            'quantity' => 'sometimes|integer|min:1|max:20',
            'price' => 'sometimes|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // If quantity changes, update product stock and order totals
        if ($request->has('quantity') && $request->quantity != $orderItem->quantity) {
            $product = $orderItem->product;
            $quantityDifference = $request->quantity - $orderItem->quantity;

            // Check stock availability
            if ($quantityDifference > 0 && $product->stock < $quantityDifference) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}"
                ], 422);
            }

            // Update product stock
            if ($quantityDifference > 0) {
                $product->decrement('stock', $quantityDifference);
            } else {
                $product->increment('stock', abs($quantityDifference));
            }

            // Update order totals
            $order = $orderItem->order;
            $priceChange = ($request->quantity * $orderItem->price) - ($orderItem->quantity * $orderItem->price);

            $order->subtotal += $priceChange;
            $order->total += $priceChange;
            $order->save();

            $orderItem->quantity = $request->quantity;
        }

        if ($request->has('price')) {
            $orderItem->price = $request->price;
        }

        if ($request->has('notes')) {
            $orderItem->notes = $request->notes;
        }

        $orderItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Order item updated successfully',
            'data' => $orderItem->load('product')
        ]);
    }

    // Get order items for vendor
    public function vendorOrderItems(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can access this endpoint'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $query = OrderItem::with(['product', 'order.user'])
            ->whereHas('order', function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            });

        // Apply filters
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->has('status')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $orderItems = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $orderItems
        ]);
    }

    // Get popular products for vendor
    public function popularProducts(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json([
                'success' => false,
                'message' => 'Only vendors can access this endpoint'
            ], 403);
        }

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $popularProducts = OrderItem::with('product')
            ->whereHas('order', function($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id)
                  ->where('status', 'delivered');
            })
            ->selectRaw('product_id, SUM(quantity) as total_quantity, COUNT(*) as total_orders')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit($request->limit ?? 10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $popularProducts
        ]);
    }
}
