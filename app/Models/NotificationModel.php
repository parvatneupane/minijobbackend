<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel as User;
class NotificationModel extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}