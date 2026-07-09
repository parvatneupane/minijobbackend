<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'attachment',
        'message_type',
        'is_seen'
    ];

    protected function casts(): array
    {
        return [
            'is_seen' => 'boolean',
        ];
    }

    // Message belongs to Chat
    public function chat()
    {
        return $this->belongsTo(
            ChatModel::class,
            'chat_id'
        );
    }

    // Sender
    public function sender()
    {
        return $this->belongsTo(
            UserModel::class,
            'sender_id'
        );
    }
}