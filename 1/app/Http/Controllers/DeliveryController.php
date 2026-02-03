<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    public function assign(Request $request, Delivery $delivery)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'delivery_person_id' => 'required|exists:users,id',
        ]);

        $delivery->delivery_person_id = $data['delivery_person_id'];
        if ($delivery->status === 'pending') {
            // keep status
        }
        $delivery->save();

        notyf()->success('Delivery assigned');
        return back();
    }

    public function pickup(Delivery $delivery)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        // Only assigned delivery person or admin can mark picked up
        if (!($user->role === 'admin' || ($user->role === 'delivery' && $delivery->delivery_person_id === $user->id))) {
            abort(403, 'Unauthorized');
        }

        if ($delivery->status !== 'pending') {
            return back()->with('error', 'Delivery cannot be picked up in current status');
        }

        $delivery->status = 'picked_up';
        $delivery->pickup_time = now();
        $delivery->save();

        notyf()->success('Delivery marked as picked up');
        return back();
    }

    public function deliver(Delivery $delivery)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        if (!($user->role === 'admin' || ($user->role === 'delivery' && $delivery->delivery_person_id === $user->id))) {
            abort(403, 'Unauthorized');
        }

        if ($delivery->status !== 'picked_up') {
            return back()->with('error', 'Delivery must be picked up before delivering');
        }

        $delivery->status = 'delivered';
        $delivery->delivery_time = now();
        $delivery->save();

        // Optionally set order status to delivered
        try {
            if ($delivery->order) {
                $delivery->order->status = 'delivered';
                if ($delivery->order->payment_status === 'pending') {
                    $delivery->order->payment_status = 'paid';
                }
                $delivery->order->save();
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to update order status after delivery: '.$e->getMessage());
        }

        notyf()->success('Delivery marked as delivered');
        return back();
    }
}
