<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConflictReplyModel extends Model
{
    protected $table = "conflict_replies";

    protected $fillable = [

        'conflict_id',

        'user_id',

        'message',

        'attachment'

    ];

    public function conflict()
    {
        return $this->belongsTo(
            ConflictModel::class,
            'conflict_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            UserModel::class,
            'user_id'
        );
    }
}