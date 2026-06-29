<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilestoneModel extends Model
{
    use HasFactory;

    protected $table = 'milestones';

    protected $fillable = [
        'contract_id',
        'milestone_name',
        'milestone_cost',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'milestone_cost' => 'decimal:2'
        ];
    }

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }
}