<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'profile_image',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

  public function freelancerProfile()
{
    return $this->hasOne(FreeLancerProfileModel::class, 'user_id');
}

public function Verification()
{
    return $this->hasOne(VerificationModel::class, 'user_id');
}

public function tasks()
{
    return $this->hasMany(
        TaskModel::class,
        'user_id'
    );
}


public function proposal()
{
    return $this->hasMany(
        ProposalModel::class,
        'user_id'
    );
}



}