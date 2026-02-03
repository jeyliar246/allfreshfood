<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\PlatformCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayoutController extends Controller
{
    /**
     * Admin: List payout requests
     */
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'admin', 403);

        $status = $request->get('status');
        $query = Payout::with('vendor.user')->latest();
        if ($status) {
            $query->where('status', $status);
        }
        $payouts = $query->paginate(15);

        return view('dashboard.payouts', compact('payouts', 'status'));
    }
    public function requestPayout(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $vendor = $user->vendor;

        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string'
        ]);

        // Check available balance
        $availableBalance = $this->getAvailableBalance($vendor->id);

        if ($request->amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 400);
        }

        $payout = Payout::create([
            'vendor_id' => $vendor->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payout requested successfully',
            'data' => $payout
        ]);
    }

    private function getAvailableBalance($vendorId)
    {
        $totalCommissions = PlatformCommission::whereHas('order', function($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->sum('commission_amount');

        $totalPayouts = Payout::where('vendor_id', $vendorId)
            ->where('status', 'completed')
            ->sum('amount');

        return $totalCommissions - $totalPayouts;
    }

    public function getPayouts(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'vendor') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $vendor = $user->vendor;
        $payouts = Payout::where('vendor_id', $vendor->id)->paginate(20);

        return response()->json(['success' => true, 'data' => $payouts]);
    }

    // Get pending payouts (admin only)
public function getPendingPayouts(Request $request)
{
    $payouts = Payout::with('vendor.user')
        ->where('status', 'pending')
        ->paginate($request->per_page ?? 20);

    return response()->json(['success' => true, 'data' => $payouts]);
}

// Process payout (admin only) - mark as paid
public function processPayout($id)
{
    $user = Auth::user();
    abort_unless($user && $user->role === 'admin', 403);

    $payout = Payout::with('vendor')->findOrFail($id);

    if (!in_array($payout->status, ['pending','approved'])) {
        notyf()->error('Payout is not in a processable status.');
        return back();
    }

    $payout->update([
        'status' => 'paid',
    ]);

    notyf()->success('Payout marked as paid.');
    return back();
}

// Approve payout (admin)
public function approve($id)
{
    $user = Auth::user();
    abort_unless($user && $user->role === 'admin', 403);

    $payout = Payout::findOrFail($id);
    if ($payout->status !== 'pending') {
        notyf()->error('Only pending payouts can be approved.');
        return back();
    }
    $payout->update(['status' => 'approved']);
    notyf()->success('Payout approved.');
    return back();
}
}
