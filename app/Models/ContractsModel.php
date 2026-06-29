<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractModel extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'proposal_id',
        'start_date',
        'end_date',
        'total_payment',
        'agreement_file',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'total_payment' => 'decimal:2'
        ];
    }

    public function proposal()
    {
        return $this->belongsTo(
            ProposalModel::class,
            'proposal_id'
        );
    }
}