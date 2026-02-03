<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    // Get all payment methods
    public function index()
    {
        $paymentMethods = PaymentMethod::all();

        return response()->json([
            'success' => true,
            'data' => $paymentMethods
        ]);
    }

    // Get specific payment method
    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $paymentMethod
        ]);
    }

    // Create new payment method (admin only)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:payment_methods,slug',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'config' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $paymentMethod = PaymentMethod::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Payment method created successfully',
            'data' => $paymentMethod
        ], 201);
    }

    // Update payment method (admin only)
    public function update(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:payment_methods,slug,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'config' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $paymentMethod->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Payment method updated successfully',
            'data' => $paymentMethod
        ]);
    }

    // Delete payment method (admin only)
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        // Check if payment method is being used
        if ($paymentMethod->transactions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete payment method that has transactions'
            ], 400);
        }

        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment method deleted successfully'
        ]);
    }

    // Toggle payment method status
    public function toggleStatus($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment method status updated',
            'data' => $paymentMethod
        ]);
    }
}
