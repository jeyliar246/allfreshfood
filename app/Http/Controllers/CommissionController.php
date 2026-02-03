<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PlatformCommission;
use App\Models\AdminCommissionRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function calculateCommission(Order $order)
    {
        $commissionRate = $this->getCommissionRate($order);
        $commissionAmount = $order->total * ($commissionRate / 100);

        $commission = PlatformCommission::create([
            'order_id' => $order->id,
            'order_amount' => $order->total,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount
        ]);

        return $commission;
    }

    private function getCommissionRate(Order $order)
    {
        // Get applicable commission rule
        $rule = AdminCommissionRule::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->first();

        return $rule ? $rule->commission_rate : 15.00; // Default 15%
    }

    public function getVendorCommissions(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $vendor = $user->vendor;
        $commissions = PlatformCommission::whereHas('order', function($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->with('order')->paginate(20);

        return response()->json(['success' => true, 'data' => $commissions]);
    }
}
