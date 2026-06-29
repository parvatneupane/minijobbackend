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
        'amount',
        'payment_method',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2'
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