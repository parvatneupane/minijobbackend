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
        'free_lancer_id',
        'description',
        'proposal_cost',
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

    public function freelancer()
    {
        return $this->belongsTo(
            FreeLancerProfileModel::class,
            'free_lancer_id'
        );
    }
}