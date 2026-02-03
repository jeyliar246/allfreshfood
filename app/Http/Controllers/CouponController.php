<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\UserCoupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    // Get available coupons
    public function index(Request $request)
    {
        $user = Auth::user();
        $now = now();

        $coupons = Coupon::where('is_active', true)
            ->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now)
            ->where(function($query) {
                $query->whereNull('max_uses')
                    ->orWhereRaw('used_count < max_uses');
            })
            ->get();

        // Check which coupons user has already used
        $usedCouponIds = UserCoupon::where('user_id', $user->id)
            ->whereNotNull('used_at')
            ->pluck('coupon_id');

        $coupons->each(function($coupon) use ($usedCouponIds) {
            $coupon->is_used = $usedCouponIds->contains($coupon->id);
            $coupon->is_available = !$coupon->is_used &&
                ($coupon->max_uses === null || $coupon->used_count < $coupon->max_uses);
        });

        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    // Apply coupon to order
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $now = now();

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->where('valid_from', '<=', $now)
            ->where('valid_until', '>=', $now)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code'
            ], 404);
        }

        // Check if coupon has reached maximum uses
        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has reached its usage limit'
            ], 400);
        }

        // Check minimum order amount
        if ($coupon->min_order_amount !== null && $request->order_amount < $coupon->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount not met for this coupon',
                'min_order_amount' => $coupon->min_order_amount
            ], 400);
        }

        // Check if user has already used this coupon
        $alreadyUsed = UserCoupon::where('user_id', $user->id)
            ->where('coupon_id', $coupon->id)
            ->whereNotNull('used_at')
            ->exists();

        if ($alreadyUsed && !$coupon->is_reusable) {
            return response()->json([
                'success' => false,
                'message' => 'You have already used this coupon'
            ], 400);
        }

        // Calculate discount amount
        $discountAmount = $this->calculateDiscount($coupon, $request->order_amount);

        // Create user coupon record (not marked as used yet)
        UserCoupon::create([
            'user_id' => $user->id,
            'coupon_id' => $coupon->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'data' => [
                'coupon' => $coupon,
                'discount_amount' => $discountAmount,
                'final_amount' => $request->order_amount - $discountAmount
            ]
        ]);
    }

    private function calculateDiscount($coupon, $orderAmount)
    {
        if ($coupon->type === 'percentage') {
            $discount = $orderAmount * ($coupon->value / 100);
            // Apply maximum discount if set
            if ($coupon->max_discount !== null) {
                $discount = min($discount, $coupon->max_discount);
            }
            return $discount;
        }

        // Fixed amount discount
        return min($coupon->value, $orderAmount);
    }

    // Mark coupon as used when order is completed
    public function markCouponUsed($couponId, $orderId)
    {
        $user = Auth::user();

        $userCoupon = UserCoupon::where('user_id', $user->id)
            ->where('coupon_id', $couponId)
            ->whereNull('used_at')
            ->first();

        if ($userCoupon) {
            $userCoupon->update([
                'order_id' => $orderId,
                'used_at' => now()
            ]);

            // Increment coupon usage count
            Coupon::where('id', $couponId)->increment('used_count');
        }

        return $userCoupon;
    }

    // Get user's coupon history
    public function getUserCoupons(Request $request)
    {
        $user = Auth::user();

        $coupons = UserCoupon::with(['coupon', 'order'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    // Admin: Create new coupon
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_discount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'is_reusable' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $coupon = Coupon::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ], 201);
    }
}
