<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'contract_id',
        'client_id',
        'freelancer_id',
        'amount',
        'platform_fee',
        'freelancer_amount',
        'payment_method',
        'transaction_id',
        'status',
        'paid_at',
        'released_at'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'freelancer_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }

    public function client()
    {
        return $this->belongsTo(
            UserModel::class,
            'client_id'
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