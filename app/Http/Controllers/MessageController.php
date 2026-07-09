<?php

namespace App\Http\Controllers;

use App\Models\ChatModel;
use App\Models\MessageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display all messages
     */
    public function index()
    {
        $messages = MessageModel::with([
            'chat',
            'sender'
        ])->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Messages fetched successfully.',
            'data' => $messages
        ]);
    }

    /**
     * Send message
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'chat_id' => 'required|exists:chats,id',

            'sender_id' => 'required|exists:users,id',

            'message' => 'nullable|string',

            'attachment' => 'nullable|file|max:10240',

            'message_type' => 'nullable|string'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        if (!$request->message && !$request->hasFile('attachment')) {

            return response()->json([
                'success' => false,
                'message' => 'Message or attachment is required.'
            ], 422);

        }

        $attachmentPath = null;

        if ($request->hasFile('attachment')) {

            $attachmentPath = $request
                ->file('attachment')
                ->store('chat_attachments', 'public');

        }

        $message = MessageModel::create([

            'chat_id' => $request->chat_id,

            'sender_id' => $request->sender_id,

            'message' => $request->message,

            'attachment' => $attachmentPath,

            'message_type' => $request->message_type ?? 'text',

            'is_seen' => false

        ]);

        // Update chat last message
        ChatModel::where('id', $request->chat_id)->update([
            'last_message' => $request->message ?? 'Attachment',
            'last_message_time' => now()
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Message sent successfully.',

            'data' => $message->load([
                'sender',
                'chat'
            ])

        ], 201);
    }

    /**
     * Show single message
     */
    public function show($id)
    {
        $message = MessageModel::with([
            'chat',
            'sender'
        ])->find($id);

        if (!$message) {

            return response()->json([
                'success' => false,
                'message' => 'Message not found.'
            ], 404);

        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Update message
     */
    public function update(Request $request, $id)
    {
        $message = MessageModel::find($id);

        if (!$message) {

            return response()->json([
                'success' => false,
                'message' => 'Message not found.'
            ], 404);

        }

        $validator = Validator::make($request->all(), [

            'message' => 'sometimes|string',

            'is_seen' => 'sometimes|boolean'

        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);

        }

        $message->update(

            $request->only([
                'message',
                'is_seen'
            ])

        );

        return response()->json([

            'success' => true,

            'message' => 'Message updated successfully.',

            'data' => $message->fresh()->load([
                'sender',
                'chat'
            ])

        ]);

    }

    /**
     * Delete message
     */
    public function destroy($id)
    {
        $message = MessageModel::find($id);

        if (!$message) {

            return response()->json([
                'success' => false,
                'message' => 'Message not found.'
            ], 404);

        }

        if ($message->attachment) {

            Storage::disk('public')->delete($message->attachment);

        }

        $message->delete();

        return response()->json([

            'success' => true,

            'message' => 'Message deleted successfully.'

        ]);
    }

    /**
     * Get all messages of a chat
     */
    public function chatMessages($chatId)
    {
        $messages = MessageModel::with('sender')
            ->where('chat_id', $chatId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([

            'success' => true,

            'data' => $messages

        ]);
    }

    /**
     * Mark all messages as seen
     */
    public function markAsRead($chatId)
    {
        MessageModel::where('chat_id', $chatId)
            ->update([
                'is_seen' => true
            ]);

        return response()->json([

            'success' => true,

            'message' => 'Messages marked as seen.'

        ]);
    }
}