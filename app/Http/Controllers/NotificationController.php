<?php

namespace App\Http\Controllers;

use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    private FCMService $fcm;

    public function __construct(FCMService $fcm)
    {
        $this->fcm = $fcm;
    }

    /**
     * Send notification to any FCM token
     */
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'title'     => 'required|string',
            'message'   => 'required|string',
            'data'      => 'sometimes|array',
        ]);

        $response = $this->fcm->sendNotification(
            $validated['fcm_token'],
            $validated['title'],
            $validated['message'],
            $validated['data'] ?? []
        );

        return response()->json($response);
    }

    /**
     * Send notification to one user
     */
    public function sendNotificationToUser($userId, $title, $message)
    {
        $user = UserModel::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        if (empty($user->fcm_token)) {
            return response()->json([
                'success' => false,
                'message' => 'User has no FCM token.'
            ], 404);
        }

        NotificationModel::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
        ]);

        $response = $this->fcm->sendNotification(
            $user->fcm_token,
            $title,
            $message
        );

        return response()->json($response);
    }

    /**
     * Send notification to all users
     */
    public function sendNotificationToAll($title, $message)
    {
        $users = UserModel::whereNotNull('fcm_token')->get();

        foreach ($users as $user) {

            NotificationModel::create([
                'user_id' => $user->id,
                'title'   => $title,
                'message' => $message,
            ]);

            $this->fcm->sendNotification(
                $user->fcm_token,
                $title,
                $message
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification sent to all users.'
        ]);
    }

    /**
     * Get notifications of a user
     */
    public function getNotification($userId)
    {
        $notifications = NotificationModel::where('user_id', $userId)
            ->latest()
            ->get();

        if ($notifications->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No notifications found.'
            ], 404);
        }

        Log::info($notifications->toArray());

        return response()->json([
            'status' => 200,
            'data' => $notifications
        ]);
    }
}