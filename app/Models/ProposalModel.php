<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalModel extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'task_id',
        'user_id',
        'description',
        'takes_time',
        'achievement',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'proposal_cost' => 'integer'
        ];
    }

    public function task()
    {
        return $this->belongsTo(
            TaskModel::class,
            'task_id'
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