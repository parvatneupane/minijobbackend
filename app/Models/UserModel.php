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

// Contracts where user is the client
public function clientContracts()
{
    return $this->hasMany(
        ContractModel::class,
        'client_id'
    );
}

// Contracts where user is the freelancer
public function freelancerContracts()
{
    return $this->hasMany(
        ContractModel::class,
        'freelancer_id'
    );
}

// Messages sent by the user
public function messages()
{
    return $this->hasMany(
        MessageModel::class,
        'sender_id'
    );
}
 
public function submissions()
{
    return $this->hasMany(
        SubmissionModel::class,
        'freelancer_id'
    );
}

// Payments made by this user (Client)
public function paymentsMade()
{
    return $this->hasMany(
        PaymentModel::class,
        'client_id'
    );
}

// Payments received by this user (Freelancer)
public function paymentsReceived()
{
    return $this->hasMany(
        PaymentModel::class,
        'freelancer_id'
    );
}

// Withdrawal requests
public function withdrawals()
{
    return $this->hasMany(
        WithdrawalModel::class,
        'user_id'
    );
}

}