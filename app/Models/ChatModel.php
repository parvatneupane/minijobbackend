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
        'sender_id',
        'receiver_id',
        'message',
        'attachment',
        'is_read',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer'
        ];
    }

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }

    public function sender()
    {
        return $this->belongsTo(
            UserModel::class,
            'sender_id'
        );
    }

    public function receiver()
    {
        return $this->belongsTo(
            UserModel::class,
            'receiver_id'
        );
    }
}