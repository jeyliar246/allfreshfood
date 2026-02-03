<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\VendorAnalytics;
use Illuminate\Validation\Rule;
use App\Models\ProductAnalytics;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Get all orders with filters
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Order::with(['user', 'vendor', 'items.product']);

        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if ($vendor) {
                $query->where('vendor_id', $vendor->id);
            }
        } elseif ($user->role === 'customer') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        $orders = $query->latest()->paginate($request->per_page ?? 15);

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // Create a new order
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'delivery_address' => 'required|string|max:500',
            'payment_method' => ['required', Rule::in(['card', 'cash', 'bank_transfer', 'wallet'])],
            'fulfillment_method' => ['required', Rule::in(['delivery', 'pickup'])],
            'distance_km' => 'nullable|numeric|min:0|required_if:fulfillment_method,delivery',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:20',
        ]);

        $vendor = Vendor::findOrFail($request->vendor_id);

        $subtotal = 0;
        $items = [];

        foreach ($request->items as $itemData) {
            $product = Product::findOrFail($itemData['product_id']);
            if ($product->vendor_id != $vendor->id) {
                return response()->json(['success' => false, 'message' => "Product {$product->name} does not belong to this vendor"], 422);
            }
            if ($product->stock < $itemData['quantity']) {
                return response()->json(['success' => false, 'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}"], 422);
            }
            $itemTotal = $product->price * $itemData['quantity'];
            $subtotal += $itemTotal;
            $items[] = [
                'product_id' => $product->id,
                'quantity' => $itemData['quantity'],
                'price' => $product->price,
                'total' => $itemTotal
            ];
        }

        $vendorCount = 1; // this endpoint creates a single-vendor order
        $isPickup = $request->fulfillment_method === 'pickup';
        if ($isPickup) {
            $deliveryFee = 0.00;
        } else {
            $distanceKm = (float) ($request->distance_km ?? 0);
            $deliveryFee = round(1.20 + 0.30 * max(0, $distanceKm) + 0.60 * max(0, $vendorCount), 2);
        }
        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'user_id' => $user->id,
            'vendor_id' => $vendor->id,
            'order_number' => (string) str()->uuid(),
            'total' => $total,
            'status' => 'pending',
            'delivery_address' => $request->delivery_address,
            'delivery_fee' => $deliveryFee,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
        }

        return response()->json(['success' => true, 'message' => 'Order created successfully', 'data' => $order->load('items.product', 'vendor')], 201);
    }

    // Get order details
    public function show($id)
    {
        $order = Order::with(['user', 'vendor', 'items.product'])->findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to order'], 403);
        }
        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor || $order->vendor_id !== $vendor->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to order'], 403);
            }
        }
        return response()->json(['success' => true, 'data' => $order]);
    }

    // Update order status (admin and owning vendor)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'customer') {
            return response()->json(['success' => false, 'message' => 'Unauthorized to update order status'], 403);
        }
        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor || $order->vendor_id !== $vendor->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized to update this order'], 403);
            }
        }

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'processing', 'preparing', 'ready', 'on_delivery', 'shipped', 'delivered', 'cancelled'])]
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;
        if ($request->status === 'delivered' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }
        $order->save();

        // Notifications
        try {
            $this->sendOrderNotifications($order, $oldStatus);
        } catch (\Throwable $e) {
            report($e);
        }

        // If the request expects JSON (API/AJAX), return JSON; otherwise redirect back for Blade forms
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order status updated successfully', 'data' => $order]);
        }

        notyf()->success('Order status updated successfully');
        return back();
    }

    // Cancel order
    public function cancel($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $user = Auth::user();
        if ($user->role === 'customer' && $order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized to cancel this order'], 403);
        }
        if ($user->role === 'vendor') {
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!$vendor || $order->vendor_id !== $vendor->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized to cancel this order'], 403);
            }
        }
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return response()->json(['success' => false, 'message' => 'Order cannot be cancelled at this stage'], 422);
        }
        $order->status = 'cancelled';
        $order->save();
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }
        return response()->json(['success' => true, 'message' => 'Order cancelled successfully', 'data' => $order]);
    }

    protected function handleOrderCompletion(Order $order)
    {
        $this->updateVendorAnalytics($order);
        $this->updateProductAnalytics($order);
        $this->sendOrderNotifications($order);
    }

    private function updateVendorAnalytics(Order $order)
    {
        $today = now()->toDateString();
        VendorAnalytics::updateOrCreate(
            ['vendor_id' => $order->vendor_id, 'date' => $today],
            ['orders_count' => DB::raw('orders_count + 1'), 'orders_total' => DB::raw('orders_total + ' . $order->total)]
        );
    }

    private function updateProductAnalytics(Order $order)
    {
        $today = now()->toDateString();
        foreach ($order->items as $item) {
            ProductAnalytics::updateOrCreate(
                ['product_id' => $item->product_id, 'date' => $today],
                ['orders_count' => DB::raw('orders_count + ' . $item->quantity)]
            );
        }
    }

    private function sendOrderNotifications(Order $order, ?string $oldStatus = null): void
    {
        // Notify customer
        $user = $order->user ?? $order->user()->first();
        if ($user && $user->email) {
            Mail::to($user->email)->send(new OrderStatusUpdated($order, $oldStatus ?? $order->getOriginal('status')));
        }
        // Optionally notify vendor
        $vendor = $order->vendor ?? $order->vendor()->first();
        if ($vendor && $vendor->email) {
            Mail::to($vendor->email)->send(new OrderStatusUpdated($order, $oldStatus ?? $order->getOriginal('status')));
        }
    }
}
