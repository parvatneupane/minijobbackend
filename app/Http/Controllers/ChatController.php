<?php

namespace App\Http\Controllers;

use App\Models\ChatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Display all chats
     */
    public function index()
    {
        $chats = ChatModel::with([
            'contract.task',
            'contract.client',
            'contract.freelancer',
            'messages.sender'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Chats fetched successfully.',
            'data' => $chats
        ]);
    }

    /**
     * Create chat room
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|exists:contracts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Prevent duplicate chat for same contract
        $exists = ChatModel::where('contract_id', $request->contract_id)->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Chat already exists.',
                'data' => $exists->load([
                    'contract.task',
                    'contract.client',
                    'contract.freelancer'
                ])
            ], 409);
        }

        $chat = ChatModel::create([
            'contract_id' => $request->contract_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Chat room created successfully.',
            'data' => $chat->load([
                'contract.task',
                'contract.client',
                'contract.freelancer'
            ])
        ], 201);
    }

    /**
     * Show single chat
     */
    public function show($id)
    {
        $chat = ChatModel::with([
            'contract.task',
            'contract.client',
            'contract.freelancer',
            'messages.sender'
        ])->find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chat
        ]);
    }

    /**
     * Update chat
     */
    public function update(Request $request, $id)
    {
        $chat = ChatModel::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'last_message' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $chat->update($request->only([
            'last_message'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Chat updated successfully.',
            'data' => $chat
        ]);
    }

    /**
     * Delete chat
     */
    public function destroy($id)
    {
        $chat = ChatModel::find($id);

        if (!$chat) {
            return response()->json([
                'success' => false,
                'message' => 'Chat not found.'
            ], 404);
        }

        $chat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Chat deleted successfully.'
        ]);
    }
}