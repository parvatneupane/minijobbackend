<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalModel extends Model
{
    use HasFactory;

    protected $table = 'withdrawals';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_method',
        'account_name',
        'account_number',
        'transaction_id',
        'status',
        'remarks',
        'requested_at',
        'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'requested_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            UserModel::class,
            'user_id'
        );
    }
}