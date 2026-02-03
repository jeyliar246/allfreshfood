<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payout;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorFinanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'vendor', 403);

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $deliveredTotal = Order::where('vendor_id', $vendor->id)
            ->where('status', 'delivered')
            ->sum('total');

        $payoutTotal = Payout::where('vendor_id', $vendor->id)
            ->whereIn('status', ['approved', 'paid'])
            ->sum('amount');

        $pendingPayouts = Payout::where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        $balance = max(0, $deliveredTotal - $payoutTotal);

        return view('vendor.finance.index', compact('vendor', 'balance', 'pendingPayouts', 'deliveredTotal', 'payoutTotal'));
    }

    public function storeWithdrawal(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'vendor', 403);
        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Compute current balance
        $deliveredTotal = Order::where('vendor_id', $vendor->id)
            ->where('status', 'delivered')
            ->sum('total');
        $payoutTotal = Payout::where('vendor_id', $vendor->id)
            ->whereIn('status', ['approved', 'paid'])
            ->sum('amount');
        $balance = max(0, $deliveredTotal - $payoutTotal);

        if ($validated['amount'] > $balance) {
            return back()->withErrors(['amount' => 'Requested amount exceeds available balance ('.$balance.').'])->withInput();
        }

        Payout::create([
            'vendor_id' => $vendor->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        notyf()->success('Withdrawal request submitted and pending admin approval.');
        return back();
    }

    public function storeBankDetails(Request $request)
    {

        $validated = $request->validate([
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'sort_code' => 'required|string|max:100',
        ]);

        $bnkDet = Vendor::where('user_id', Auth::user()->id)->firstOrFail();
        $bnkDet->update([
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'],
            'sort_code' => $validated['sort_code'],
        ]);

        notyf()->success('Bank details updated successfully.');
        return back();
    }
    
}
