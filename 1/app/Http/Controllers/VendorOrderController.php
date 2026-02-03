<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'vendor', 403);

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        $orders = Order::with(['user','items.product'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.orders.index', compact('vendor', 'orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'vendor', 403);

        $vendor = Vendor::where('user_id', $user->id)->firstOrFail();
        abort_if($order->vendor_id !== $vendor->id, 403);

        $order->load(['user', 'items.product']);
        return view('vendor.orders.show', compact('vendor', 'order'));
    }
}
