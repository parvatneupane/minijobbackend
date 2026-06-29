<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    // Get all payments
    public function index()
    {
        $payments = PaymentModel::with(
            'contract'
        )->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    // Create payment
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' =>
                'required|exists:contracts,id',

            'amount' =>
                'required|numeric',

            'payment_method' =>
                'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payment = PaymentModel::create([
            'contract_id' => $request->contract_id,
            'amount' => $request->amount,
            'payment_method' =>
                $request->payment_method,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment
        ], 201);
    }

    // Get single payment
    public function show($id)
    {
        $payment = PaymentModel::with(
            'contract'
        )->find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    // Update payment
    public function update(Request $request, $id)
    {
        $payment = PaymentModel::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'amount' =>
                'sometimes|numeric',

            'payment_method' =>
                'sometimes|string|max:255',

            'status' =>
                'sometimes|in:pending,completed,failed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $payment->update(
            $request->only([
                'amount',
                'payment_method',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'data' => $payment
        ]);
    }

    // Delete payment
    public function destroy($id)
    {
        $payment = PaymentModel::find($id);

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ]);
    }
}