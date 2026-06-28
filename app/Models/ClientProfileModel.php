<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProfileModel extends Model
{
    use HasFactory;

    protected $table = 'client_profiles';

    protected $fillable = [
        'user_id',
        'client_name',
        'profile_image',
        'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}