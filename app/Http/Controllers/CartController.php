<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\UserMail;
use App\Models\Vendor;
use App\Mail\AdminMail;
use App\Models\Product;
use Nette\Utils\Random;
use App\Mail\VendorMail;
use App\Models\OrderItem;
use App\Models\Delivery;
use App\Models\DeliveryAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    /**
     * Calculate total delivery fee for the checkout
     * Base £1.20 + £0.30 per Km + £0.60 per store/vendor
     */
    private function getConfiguredDeliveryAmount(): float
    {
        return (float) (DeliveryAmount::orderByDesc('created_at')->value('amount') ?? 0);
    }



    /**
     * Generate a unique order number
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Random::generate(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
    /**
     * Show cart page
     */
    public function cart()
    {
        $cartItems = Cart::content();
        $count = Cart::count();

        // Check for out-of-stock or low-stock items
        $hasOutOfStock = false;
        $hasLowStock = false;
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            if ($product) {
                if ($product->stock <= 0) {
                    $hasOutOfStock = true;
                }
                if ($product->stock > 0 && $product->stock <= 5) {
                    $hasLowStock = true;
                }
            }
        }

        return view('cart.cart', [
            'cartItems' => $cartItems,
            'subtotal'  => Cart::subtotal(),
            'tax'       => Cart::tax(),
            'total'     => Cart::total(),
            'count'     => $count,
            'hasOutOfStock' => $hasOutOfStock,
            'hasLowStock' => $hasLowStock,
        ]);
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1'
        ]);

        $product = Product::findOrFail($data['product_id']);
        $qty = $data['quantity'] ?? 1;

        if ($product->stock < $qty) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $product->stock . ' available.',
                ], 400);
            }
            notyf()->error('Insufficient stock. Only ' . $product->stock . ' available.');
            return redirect()->back();
        }

        $cartItem = Cart::add(
            $product->id,
            $product->name,
            $qty,
            $product->pprice,
            [
                'image'     => $product->image ? asset('uploads/' . $product->image) : null,
                'vendor_id' => $product->vendor_id,
            ]
        )->associate(Product::class);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Added to cart',
                'cart_count' => Cart::count(),
                'cart_item'  => $cartItem,
                'subtotal'   => Cart::subtotal(),
                'tax'        => Cart::tax(),
                'total'      => Cart::total(),
            ]);
        }

        notyf()->success('Product added to cart');
        return redirect()->back();
    }

    /**
     * Update item quantity
     */

     public function update(Request $request)
    {
        $data = $request->validate([
            'rowId'    => 'required|string',
            'quantity' => 'required|integer|min:1',
            'action'   => 'nullable|in:increase,decrease'
        ]);

        $cartItem = Cart::get($data['rowId']);
        if (!$cartItem) {
            return back()->with('error', 'Item not found');
        }

        $product = $cartItem->model;
        $newQty = $data['quantity'];
        
        // Handle action if provided (for button clicks)
        if ($request->action === 'increase') {
            $newQty = $cartItem->qty + 1;
        } elseif ($request->action === 'decrease') {
            $newQty = max(1, $cartItem->qty - 1);
        }
        
        if ($product && $product->stock < $newQty) {
            return back()->with('error', 'Insufficient stock. Only ' . $product->stock . ' left.');
        }

        Cart::update($data['rowId'], $newQty);

        notyf()->success('Cart updated successfully');
        return back();
    }

    /**
     * Remove an item from the cart
     */
    public function remove(Request $request)
    {
        $data = $request->validate(['rowId' => 'required|string']);

        Cart::remove($data['rowId']);

        notyf()->success('Item removed');
        return back();
    }

    /**
     * Clear all items from the cart
     */
    public function clear(Request $request)
    {
        Cart::destroy();

        notyf()->success('Cart cleared');
        return back();
    }



    /**
     * Checkout page
     */
    public function checkout()
    {
        if (Cart::count() == 0) {
            return redirect()->route('home.cart')->with('error', 'Your cart is empty');
        }

        $cartItems = Cart::content();
        $vendors   = $cartItems->groupBy('options.vendor_id');

        $deliveryAmount = $this->getConfiguredDeliveryAmount();

        return view('cart.checkout', [
            'cartItems' => $cartItems,
            'vendors'   => $vendors,
            'subtotal'  => Cart::subtotal(),
            'tax'       => Cart::tax(),
            'total'     => Cart::total(),
            'count'     => Cart::count(),
            'deliveryAmount' => $deliveryAmount,
        ]);
    }

    /**
     * Handle checkout and create orders (for non-card payments)
     */

public function processCheckout(Request $request)
{
    Log::info('processCheckout called', ['user_id' => Auth::id(), 'method' => $request->method, 'payment_method' => $request->payment_method]);

    if (Cart::count() == 0) {
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty'], 400);
        }
        return redirect()->route('home.cart')->with('error', 'Your cart is empty');
    }



    $data = $request->all();
    if (!isset($data['delivery_address']) || empty(trim($data['delivery_address']))) {
        $data['fulfillment_method'] = 'pickup';
        $data['delivery_address'] = null;
    }
    if (!isset($data['distance_km']) || empty(trim($data['distance_km']))) {
        $data['distance_km'] = 0;
    }

        // Common validation
    $rules = [
        'delivery_address'    => 'nullable|string|max:500|required_if:fulfillment_method,delivery',
        'payment_method'      => 'required|in:card,cash,bank_transfer,wallet',
        'phone'               => 'nullable|string|max:20',
        'fulfillment_method'  => 'required|in:delivery,pickup',
    ];
    if (!Auth::check()) {
        $rules = array_merge($rules, [
            'guest_name'  => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:20',
        ]);
    }

    $validator = \Validator::make($data, $rules);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }
        return back()->withErrors($validator)->withInput();
    }

    $user = Auth::user();
    $cartItems = Cart::content();
    $vendors = $cartItems->groupBy('options.vendor_id');

    if ($request->payment_method === 'card') {
        // Card: Create Stripe session and redirect (server-side)
        return $this->handleCardPayment($request, $user, $cartItems, $vendors);
    } else {
        // Non-card: Process order directly
        return $this->handleNonCardPayment($request, $user, $cartItems, $vendors);
    }
}

/**
 * Handle card payment (create Stripe session and redirect)
 */
private function handleCardPayment(Request $request, $user, $cartItems, $vendors)
{
    // Save delivery details for post-payment order creation
    session(['checkout_data' => [
        'delivery_address' => $request->delivery_address,
        'phone' => $request->phone ?? $request->guest_phone,
        'fulfillment_method' => $request->fulfillment_method,
        'guest_name' => $request->guest_name,
        'guest_email' => $request->guest_email,
        'guest_phone' => $request->guest_phone,
    ]]);

    $currency = strtolower(config('cashier.currency', 'gbp'));

    $lineItems = [];
    
    // Add cart items as line items
    foreach ($cartItems as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => $item->name,
                    'description' => 'Product ID: ' . $item->id,
                    'images' => [$item->options->image ?? 'https://via.placeholder.com/150'],
                ],
                'unit_amount' => (int) round($item->price * 100),
            ],
            'quantity' => (int) $item->qty,
        ];
    }

    // Flat delivery amount from configuration
    $vendorCount = count($vendors);
    $isPickup = $request->fulfillment_method === 'pickup';
    $configured = $this->getConfiguredDeliveryAmount();
    $totalDeliveryFee = $isPickup ? 0.0 : (float) $configured;

    if ($totalDeliveryFee > 0) {
        $lineItems[] = [
            'price_data' => [
                'currency' => $currency,
                'product_data' => [
                    'name' => 'Delivery Fee',
                ],
                'unit_amount' => (int) round($totalDeliveryFee * 100),
            ],
            'quantity' => 1,
        ];
    }

    try {
        \Stripe\Stripe::setApiKey(config('cashier.secret'));
        
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'customer_email' => $user?->email ?? ($request->guest_email ?? null),
            'line_items' => $lineItems,
            'metadata' => [
                'user_id' => (string) ($user->id ?? 0),
                'cart_count' => (string) Cart::count(),
            ],
            'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payments.cancel'),
            'billing_address_collection' => 'auto',
            'phone_number_collection' => ['enabled' => true],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'url' => $session->url]);
        }

        // Server-side redirect (bypasses JS)
        return redirect($session->url);

    } catch (\Exception $e) {
        Log::error('Stripe Checkout Session Error: ' . $e->getMessage());
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create checkout session'
            ], 500);
        }
        return back()->with('error', 'Failed to start payment. Please try again.');
    }
}

/**
 * Handle non-card payment (create orders, etc.)
 */
private function handleNonCardPayment(Request $request, $user, $cartItems, $vendors)
{
    $createOrders = [];

    try {
        DB::beginTransaction();

        // Compute allocation of the configured delivery fee across vendors (even split)
        $vendorCount = count($vendors);
        $isPickup = $request->fulfillment_method === 'pickup';
        $configured = $this->getConfiguredDeliveryAmount();
        $totalDeliveryFee = $isPickup ? 0.0 : (float) $configured;

        $vendorSubtotals = [];
        foreach ($vendors as $vId => $vItems) {
            $vSubtotal = $vItems->sum(fn($i) => $i->price * $i->qty);
            $vendorSubtotals[$vId] = $vSubtotal;
        }

        foreach ($vendors as $vendorId => $items) {
            $subtotal = $vendorSubtotals[$vendorId] ?? 0.0;

            // Even split across vendors
            $deliveryFee = $vendorCount > 0 ? round($totalDeliveryFee / $vendorCount, 2) : 0.0;

            $orderNumber = $this->generateOrderNumber();

            $total = $subtotal + $deliveryFee;
            $ptotal = $subtotal + $deliveryFee;

            $order = Order::create([
                'user_id'          => $user->id ?? null,
                'vendor_id'        => $vendorId,
                'order_number'     => $orderNumber,
                'delivery_fee'     => $deliveryFee,
                'total'            => $total,
                'ptotal'            => $ptotal,
                'payment_method'   => $request->payment_method,
                'delivery_address' => $request->delivery_address,
                'phone'            => $request->phone ?? $request->guest_phone,
                'fulfillment_method' => $request->fulfillment_method,
                'status'           => 'pending',
                'payment_status'   => 'pending',
                'guest_name'       => $user ? null : $request->guest_name,
                'guest_email'      => $user ? null : $request->guest_email,
                'guest_phone'      => $user ? null : $request->guest_phone,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->id,
                    'quantity'   => $item->qty,
                    'price'      => $item->price,
                    'pprice'      => $item->pprice,
                ]);

                $product = Product::find($item->id);
                if ($product) {
                    $product->decrement('stock', $item->qty);
                }
            }

            // Create delivery task if fulfillment is delivery
            if (($request->fulfillment_method ?? 'delivery') === 'delivery') {
                Delivery::create([
                    'order_id' => $order->id,
                    'vendor_id' => $vendorId,
                    'delivery_person_id' => null,
                    'status' => 'pending',
                    'delivery_address' => $order->delivery_address,
                    'delivery_notes' => null,
                    'tracking_number' => null,
                ]);
            }

            $createOrders[] = $order->load('items.product', 'vendor', 'delivery');
        }

        session(['recent_orders' => $createOrders]);
        Cart::destroy();

        DB::commit();

        // Send emails
        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminMail('New Orders Placed', [
                    'user' => $user,
                    'orders' => $createOrders,
                ]));
            }

            if ($user->email) {
                Mail::to($user->email)->send(new UserMail('Your order confirmation', [
                    'user' => $user,
                    'orders' => $createOrders,
                ]));
            }

            foreach ($createOrders as $order) {
                $vendor = $order->vendor;
                if ($vendor && $vendor->email) {
                    Mail::to($vendor->email)->send(new VendorMail('New order received', [
                        'vendor' => $vendor,
                        'order' => $order,
                    ]));
                }
            }
        } catch (\Throwable $mailEx) {
            Log::warning('Email dispatch failed after checkout: ' . $mailEx->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'orders'  => $createOrders,
            ]);
        }

        notyf()->success('Order placed successfully');
        return redirect()->route('order-confirmation');

    } catch (\Throwable $th) {
        DB::rollBack();
        Log::error('Checkout failed: ' . $th->getMessage());
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Failed to place order: ' . $th->getMessage()], 500);
        }
        notyf()->error('Failed to place order');
        return redirect()->route('home.cart');
    }
}


    public function orderConfirmation()
    {
        if (!session()->has('recent_orders')) {
            return redirect()->route('home')->with('info', 'No recent orders found.');
        }

        $orders = session('recent_orders');
        return view('cart.orderConfirmation', compact('orders'));
    }

    /**
     * Display a list of the authenticated user's orders.
     */
    public function orders(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home.login');
        }

        $query = Order::with(['items.product', 'vendor'])
            ->where('user_id', Auth::id());

        // Optional status filter: pending, processing, delivered, cancelled
        $status = $request->query('status');
        $validStatuses = ['pending', 'processing', 'delivered', 'cancelled'];
        if ($status && in_array($status, $validStatuses, true)) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('cart.orders', compact('orders', 'status'));
    }
}