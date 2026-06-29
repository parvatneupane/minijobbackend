<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ChatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    // Get all chats
    public function index()
    {
        $chats = ChatModel::with([
            'contract',
            'sender',
            'receiver'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }

    // Create chat
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'contract_id' =>
                'required|exists:contracts,id',

            'sender_id' =>
                'required|exists:users,id',

            'receiver_id' =>
                'required|exists:users,id',

            'message' =>
                'required|string',

            'attachment' =>
                'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $chat = ChatModel::create([
            'contract_id' => $request->contract_id,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'attachment' => $request->attachment,
            'is_read' => '0',
            'status' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $chat
        ], 201);
    }

    // Get single chat
    public function show($id)
    {
        $chat = ChatModel::with([
            'contract',
            'sender',
            'receiver'
        ])->find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chat
        ]);
    }

    // Update chat
    public function update(Request $request, $id)
    {
        $chat = ChatModel::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [

            'message' =>
                'sometimes|string',

            'attachment' =>
                'nullable|string',

            'is_read' =>
                'sometimes|in:0,1',

            'status' =>
                'sometimes|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $chat->update(
            $request->only([
                'message',
                'attachment',
                'is_read',
                'status'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Chat updated successfully',
            'data' => $chat
        ]);
    }

    // Delete chat
    public function destroy($id)
    {
        $chat = ChatModel::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found'
            ], 404);
        }

        $chat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat deleted successfully'
        ]);
    }
}