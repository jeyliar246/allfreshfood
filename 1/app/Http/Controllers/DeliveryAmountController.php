<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryAmount;
use Illuminate\Support\Facades\Auth;

class DeliveryAmountController extends Controller
{
    public function index()
    {
        $deliveryAmounts = DeliveryAmount::orderByDesc('created_at')->get();
        $current = $deliveryAmounts->first();
        return view('dashboard.delAmount', compact('deliveryAmounts', 'current'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'outside' => 'nullable|numeric|min:0',
        ]);

        $userId = Auth::id() ?? 0;

        DeliveryAmount::create([
            'amount' => $data['amount'],
            'outside' => $data['outside'],
            'created_by' => $userId,
            'updated_by' => $userId,
        ]);

        notyf()->success('Delivery amount added');
        return back();
    }

    public function edit(DeliveryAmount $deliveryAmount)
    {
        return view('dashboard.delAmount_edit', compact('deliveryAmount'));
    }

    public function update(Request $request, DeliveryAmount $deliveryAmount)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'outside' => 'nullable|numeric|min:0',
        ]);

        $deliveryAmount->amount = $data['amount'];
        $deliveryAmount->outside = $data['outside'];
        $deliveryAmount->updated_by = Auth::id() ?? $deliveryAmount->updated_by;
        $deliveryAmount->save();

        notyf()->success('Delivery amount updated');
        return redirect()->route('delivery.amounts');
    }
}
