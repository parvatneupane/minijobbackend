<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionModel extends Model
{
    use HasFactory;

    protected $table="submissions";

    protected $fillable=[
        'contract_id',
        'freelancer_id',
        'message',
        'attachment',
        'status',
        'client_feedback',
        'submitted_at',
        'approved_at'
    ];

    protected function casts():array
    {
        return[
            'submitted_at'=>'datetime',
            'approved_at'=>'datetime'
        ];
    }

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }

    public function freelancer()
    {
        return $this->belongsTo(
            UserModel::class,
            'freelancer_id'
        );
    }
}