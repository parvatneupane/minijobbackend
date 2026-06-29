<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewModel extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'contract_id',
        'reviewer_id',
        'reviewee_id',
        'rating',
        'comment',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'status' => 'integer'
        ];
    }

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(
            UserModel::class,
            'reviewer_id'
        );
    }

    public function reviewee()
    {
        return $this->belongsTo(
            UserModel::class,
            'reviewee_id'
        );
    }
}