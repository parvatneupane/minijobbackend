<?php

namespace App\Http\Controllers;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

use App\Models\PaymentModel;
use App\Models\FreeLancerProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display all payments
     */
    public function index()
    {
        $payments = PaymentModel::with([
            'contract',
            'client',
            'freelancer'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    /**
     * Create Payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' => 'required|exists:contracts,id',

            'client_id' => 'required|exists:users,id',

            'freelancer_id' => 'required|exists:users,id',

            'amount' => 'required|numeric|min:1',

            'payment_method' => 'required|string'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ],422);

        }

        $amount = $request->amount;

        // 10% Platform Fee
        $platformFee = $amount * 0.10;

        $freelancerAmount = $amount - $platformFee;

        $payment = PaymentModel::create([

            'contract_id' => $request->contract_id,

            'client_id' => $request->client_id,

            'freelancer_id' => $request->freelancer_id,

            'amount' => $amount,

            'platform_fee' => $platformFee,

            'freelancer_amount' => $freelancerAmount,

            'payment_method' => $request->payment_method,

            // Esewa transaction id
            'transaction_id' => $request->transaction_id,

            // After successful payment
            'status' => 'escrow',

            'paid_at' => now()

        ]);

        return response()->json([

            'success'=>true,

            'message'=>'Payment successful and kept in escrow.',

            'data'=>$payment

        ],201);
    }

    /**
     * Show Single Payment
     */
    public function show($id)
    {
        $payment = PaymentModel::with([
            'contract',
            'client',
            'freelancer'
        ])->find($id);

        if(!$payment){

            return response()->json([
                'success'=>false,
                'message'=>'Payment not found.'
            ],404);

        }

        return response()->json([
            'success'=>true,
            'data'=>$payment
        ]);
    }

    /**
     * Update Payment
     */
    public function update(Request $request,$id)
    {
        $payment = PaymentModel::find($id);

        if(!$payment){

            return response()->json([
                'success'=>false,
                'message'=>'Payment not found.'
            ],404);

        }

        $validator = Validator::make($request->all(),[

            'payment_method'=>'sometimes|string',

            'transaction_id'=>'sometimes|string',

            'status'=>'sometimes|in:pending,escrow,released,failed,refunded'

        ]);

        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);

        }

        $payment->update(

            $request->only([
                'payment_method',
                'transaction_id',
                'status'
            ])

        );

        return response()->json([

            'success'=>true,

            'message'=>'Payment updated successfully.',

            'data'=>$payment

        ]);
    }

    /**
     * Release Payment
     * Client approves completed work
     */
public function release($id)
{
    $payment = PaymentModel::find($id);

    if (!$payment) {
        return response()->json([
            'success' => false,
            'message' => 'Payment not found.'
        ], 404);
    }

    if ($payment->status != 'escrow') {
        return response()->json([
            'success' => false,
            'message' => 'Payment is not in escrow.'
        ], 400);
    }

    // Release payment
    $payment->status = 'released';
    $payment->released_at = now();
    $payment->save();

    // Update freelancer profile
    $profile = FreeLancerProfileModel::where(
        'user_id',
        $payment->freelancer_id
    )->first();

    if ($profile) {

        $profile->earned_money += $payment->freelancer_amount;
        $profile->completed_jobs += 1;
        $profile->save();
    }

    // ===========================
    // Save Notification
    // ===========================
    NotificationModel::create([
        'user_id' => $payment->freelancer_id,
        'title' => 'Payment Released',
        'message' => 'Congratulations! Rs. ' .
            $payment->freelancer_amount .
            ' has been released to your account.'
    ]);

    // ===========================
    // Send Push Notification
    // ===========================
    try {

        $freelancer = UserModel::find($payment->freelancer_id);

        if ($freelancer && !empty($freelancer->fcm_token)) {

            app(FCMService::class)->sendNotification(
                $freelancer->fcm_token,
                'Payment Released and work mark as completed',
                'Congratulations! Rs. ' .
                $payment->freelancer_amount .
                ' has been released to your account.'
            );

        }

    } catch (\Exception $e) {

        Log::error('FCM Payment Release Error: ' . $e->getMessage());

    }

    return response()->json([
        'success' => true,
        'message' => 'Payment released successfully.',
        'data' => $payment
    ]);
}

    /**
     * Refund Payment
     */
public function refund($id)
{
    $payment = PaymentModel::find($id);

    if (!$payment) {

        return response()->json([
            'success' => false,
            'message' => 'Payment not found.'
        ], 404);

    }

    $payment->status = 'refunded';
    $payment->save();

    // ===========================
    // Save Notification
    // ===========================
    NotificationModel::create([
        'user_id' => $payment->client_id,
        'title' => 'Payment Refunded',
        'message' => 'Your payment of Rs. ' .
            $payment->amount .
            ' has been refunded successfully.'
    ]);

    // ===========================
    // Send Push Notification
    // ===========================
    try {

        $client = UserModel::find($payment->client_id);

        if ($client && !empty($client->fcm_token)) {

            app(FCMService::class)->sendNotification(
                $client->fcm_token,
                'Payment Refunded',
                'Your payment of Rs. ' .
                $payment->amount .
                ' has been refunded successfully.'
            );

        }

    } catch (\Exception $e) {

        Log::error('FCM Refund Error: ' . $e->getMessage());

    }

    return response()->json([

        'success' => true,

        'message' => 'Payment refunded.',

        'data' => $payment

    ]);
}

    /**
     * Delete Payment
     */
    public function destroy($id)
    {
        $payment = PaymentModel::find($id);

        if(!$payment){

            return response()->json([
                'success'=>false,
                'message'=>'Payment not found.'
            ],404);

        }

        $payment->delete();

        return response()->json([

            'success'=>true,

            'message'=>'Payment deleted successfully.'

        ]);
    }
}