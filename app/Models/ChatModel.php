<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatModel extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'contract_id',
        'last_message',
        'last_message_time'
    ];

    protected function casts(): array
    {
        return [
            'last_message_time' => 'datetime',
        ];
    }

    // Chat belongs to Contract
    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }



    
    // Chat has many Messages
    public function messages()
    {
        return $this->hasMany(
            MessageModel::class,
            'chat_id'
        );
    }
}