<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    use HasFactory;

    protected $table = "reviews";

    protected $fillable = [

        'contract_id',

        'task_id',

        'client_id',

        'freelancer_id',

        'rating',

        'review',

        'recommended'

    ];

    protected $casts = [

        'recommended' => 'boolean'

    ];

    public function contract()
    {
        return $this->belongsTo(ContractModel::class, 'contract_id');
    }

    public function task()
    {
        return $this->belongsTo(TaskModel::class, 'task_id');
    }

    public function client()
    {
        return $this->belongsTo(UserModel::class, 'client_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(UserModel::class, 'freelancer_id');
    }
}