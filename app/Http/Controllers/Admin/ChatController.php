<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatModel as Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::with('contract.client', 'contract.freelancer')
            ->latest('last_message_time')
            ->paginate(15);

        return view('admin.chats.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $chat->load('contract.client', 'contract.freelancer');
        $messages = $chat->messages()->with('sender')->orderBy('created_at')->get();

        return view('admin.chats.show', compact('chat', 'messages'));
    }
}
