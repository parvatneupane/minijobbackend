<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'task_id',
        'proposal_id',
        'client_id',
        'freelancer_id',
        'start_date',
        'deadline',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'deadline' => 'date',
        ];
    }

    // Task
    public function task()
    {
        return $this->belongsTo(
            TaskModel::class,
            'task_id'
        );
    }

    // Proposal
    public function proposal()
    {
        return $this->belongsTo(
            ProposalModel::class,
            'proposal_id'
        );
    }

    // Client
    public function client()
    {
        return $this->belongsTo(
            UserModel::class,
            'client_id'
        );
    }

    // Freelancer
    public function freelancer()
    {
        return $this->belongsTo(
            UserModel::class,
            'freelancer_id'
        );
    }

    // One Contract -> One Chat
    public function chat()
    {
        return $this->hasOne(
            ChatModel::class,
            'contract_id'
        );
    }

    public function submissions()
{
    return $this->hasMany(
        SubmissionModel::class,
        'contract_id'
    );
}

public function payment()
{
    return $this->hasOne(
        PaymentModel::class,
        'contract_id'
    );
}

}