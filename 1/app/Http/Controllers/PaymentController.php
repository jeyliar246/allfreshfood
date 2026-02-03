<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\DeliveryAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminMail;
use App\Mail\UserMail;
use App\Mail\VendorMail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Create Stripe Payment Intent (for custom Elements if needed; optional for Checkout)
     */
    public function createPaymentIntent(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (Cart::count() == 0) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        // Calculate total including vendor delivery fees
        $cartItems = Cart::content();
        $vendors   = $cartItems->groupBy('options.vendor_id');
        
        $totalDeliveryFee = 0;
        foreach ($vendors as $vendorId => $items) {
            $vendorSubtotal = $items->sum(fn($i) => $i->price * $i->qty);
            $vendor = Vendor::find($vendorId);
            $fee = $vendor?->delivery_fee ?? 0;
            
            if ($vendor && $vendor->free_delivery_over && $vendorSubtotal >= $vendor->free_delivery_over) {
                $fee = 0;
            }
            $totalDeliveryFee += $fee;
        }

        // Parse cart totals (remove commas from formatted strings)
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        $tax = floatval(str_replace(',', '', Cart::tax()));
        $total = $subtotal + $totalDeliveryFee + $tax;

        try {
            Stripe::setApiKey(config('cashier.secret'));
            $currency = config('cashier.currency', 'gbp');

            $intent = \Stripe\PaymentIntent::create([
                'amount' => (int) round($total * 100), // Amount in cents
                'currency' => $currency,
                'metadata' => [
                    'user_id' => (string) $user->id,
                    'cart_count' => (string) Cart::count(),
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $intent->client_secret,
            ]);
        } catch (\Exception $e) {
            Log::error('Stripe Payment Intent Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent'
            ], 500);
        }
    }

    /**
     * Create Stripe Checkout Session (for card payments)
     */
    public function createCheckoutSession(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'phone'            => 'required|string|max:20',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        if (Cart::count() == 0) {
            return response()->json(['success' => false, 'message' => 'Cart is empty'], 400);
        }

        // Save delivery details for order creation after success
        session(['checkout_data' => [
            'delivery_address' => $request->delivery_address,
            'phone' => $request->phone,
        ]]);

        $cartItems = Cart::content();
        $vendors   = $cartItems->groupBy('options.vendor_id');
        $currency  = strtolower(config('cashier.currency', 'gbp'));

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

        // Add delivery fees as separate line items per vendor
        foreach ($vendors as $vendorId => $items) {
            $vendorSubtotal = $items->sum(fn($i) => $i->price * $i->qty);
            $vendor = Vendor::find($vendorId);
            $fee = $vendor?->delivery_fee ?? 0;
            
            if ($vendor && $vendor->free_delivery_over && $vendorSubtotal >= $vendor->free_delivery_over) {
                $fee = 0;
            }
            
            if ($fee > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => 'Delivery Fee - ' . ($vendor?->name ?? 'Vendor'),
                        ],
                        'unit_amount' => (int) round($fee * 100),
                    ],
                    'quantity' => 1,
                ];
            }
        }

        try {
            Stripe::setApiKey(config('cashier.secret'));
            
            $session = CheckoutSession::create([
                'mode' => 'payment',
                'customer_email' => $user->email,
                'line_items' => $lineItems,
                'metadata' => [
                    'user_id' => (string) $user->id,
                    'cart_count' => (string) Cart::count(),
                ],
                'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.cancel'),
                'billing_address_collection' => 'auto',
                'phone_number_collection' => ['enabled' => true],
            ]);

            return response()->json(['success' => true, 'url' => $session->url]);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout Session Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create checkout session'
            ], 500);
        }
    }

    /**
     * Handle successful payment (create orders after Stripe success)
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('checkout')->with('error', 'Invalid payment session');
        }

        try {
            Stripe::setApiKey(config('cashier.secret'));
            $session = CheckoutSession::retrieve($sessionId);
            
            if (!$session || ($session->payment_status ?? '') !== 'paid') {
                return redirect()->route('checkout')
                    ->with('error', 'Payment not completed. Please try again.');
            }

            $user = Auth::user();

            $cartItems = Cart::content();
            if ($cartItems->count() === 0) {
                return redirect()->route('home')
                    ->with('info', 'Your cart is empty');
            }

            $vendors = $cartItems->groupBy('options.vendor_id');
            $checkoutData = session('checkout_data', []);
            $fulfillmentMethod = $checkoutData['fulfillment_method'] ?? 'pickup';

            // if (empty($checkoutData['delivery_address'])) {
            //     return redirect()->route('checkout')
            //         ->with('error', 'Delivery information missing');
            // }


            $createdOrders = [];

            DB::beginTransaction();

            $fulfillmentMethod = $checkoutData['fulfillment_method'] ?? 'pickup';
            $deliveryAddress   = $checkoutData['delivery_address'] ?? null;
            $guestName  = $checkoutData['guest_name']  ?? null;
            $guestEmail = $checkoutData['guest_email'] ?? null;
            $guestPhone = $checkoutData['guest_phone'] ?? null;

            // Flat delivery from configuration, even split across vendors
            $configuredDelivery = (float) (DeliveryAmount::orderByDesc('created_at')->value('amount') ?? 0);
            $isPickup = $fulfillmentMethod === 'pickup';
            $totalDeliveryFee = $isPickup ? 0.0 : $configuredDelivery;
            $vendorCount = max(1, count($vendors));
            $perVendorDelivery = $isPickup ? 0.0 : round($totalDeliveryFee / $vendorCount, 2);

            foreach ($vendors as $vendorId => $items) {
                $subtotal = $items->sum(fn($i) => $i->price * $i->qty);
                $vendor = Vendor::findOrFail($vendorId);
                $deliveryFee = $perVendorDelivery;
                $total = $subtotal + $deliveryFee;

                $order = Order::create([
                    'user_id'          => $user->id ?? null,
                    'vendor_id'        => $vendorId,
                    'order_number'     => $this->generateOrderNumber(),
                    'delivery_fee'     => $deliveryFee,
                    'total'            => $total,
                    'payment_method'   => 'card',
                    'delivery_address' => $deliveryAddress,
                    'phone'            => $checkoutData['phone'] ?? $guestPhone,
                    'fulfillment_method' => $fulfillmentMethod,
                    'status'           => 'pending',
                    'payment_status'   => 'paid',
                    'guest_name'       => $user ? null : $guestName,
                    'guest_email'      => $user ? null : $guestEmail,
                    'guest_phone'      => $user ? null : $guestPhone,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->id,
                        'quantity'   => $item->qty,
                        'price'      => $item->price,
                    ]);
                    
                    // Reduce stock
                    $product = Product::find($item->id);
                    if ($product) {
                        $product->decrement('stock', $item->qty);
                    }
                }

                // Create delivery task if fulfillment is delivery
                if ($fulfillmentMethod === 'delivery') {
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

                $createdOrders[] = $order->load('items.product', 'vendor', 'delivery');
            }

            // Store orders in session
            session(['recent_orders' => $createdOrders]);

            // Clear cart
            Cart::destroy();
            session()->forget('checkout_data');

            DB::commit();

            // Send email notifications
            // Determine email recipient: user or guest
            $notifyUser = $user ?: (object) ['email' => $guestEmail, 'name' => $guestName];
            $this->sendOrderNotifications($notifyUser, $createdOrders);

            notyf()->success('Order placed successfully!');
            return redirect()->route('order-confirmation');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed after payment: ' . $e->getMessage());
            
            return redirect()->route('checkout')
                ->with('error', 'Failed to process order. Please contact support.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        session()->forget('checkout_data');
        return redirect()->route('checkout')
            ->with('info', 'Payment was cancelled. You can try again when ready.');
    }

    /**
     * Send order notification emails
     */
    private function sendOrderNotifications($user, $orders)
    {
        try {
            // Admin email
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminMail('New Orders Placed', [
                    'user' => $user,
                    'orders' => $orders,
                ]));
            }

            // User email
            if ($user->email) {
                Mail::to($user->email)->send(new UserMail('Your order confirmation', [
                    'user' => $user,
                    'orders' => $orders,
                ]));
            }

            // Vendor emails
            foreach ($orders as $order) {
                $vendor = $order->vendor;
                if ($vendor && $vendor->email) {
                    Mail::to($vendor->email)->send(new VendorMail('New order received', [
                        'vendor' => $vendor,
                        'order' => $order,
                    ]));
                }
            }
        } catch (\Throwable $mailEx) {
            Log::warning('Email dispatch failed: ' . $mailEx->getMessage());
        }
    }
}