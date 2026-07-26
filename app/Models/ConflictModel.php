<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConflictModel extends Model
{
    use HasFactory;

    protected $table = 'conflicts';

    protected $fillable = [

        'contract_id',

        'raised_by',

        'against_user',

        'raised_by_role',

        'title',

        'reason',

        'attachment',

        'status',

        'admin_response'

    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function contract()
    {
        return $this->belongsTo(
            ContractModel::class,
            'contract_id'
        );
    }

    public function raisedByUser()
    {
        return $this->belongsTo(
            UserModel::class,
            'raised_by'
        );
    }

    public function againstUser()
    {
        return $this->belongsTo(
            UserModel::class,
            'against_user'
        );
    }

    public function replies()
{
    return $this->hasMany(
        ConflictReplyModel::class,
        'conflict_id'
    );
}
}