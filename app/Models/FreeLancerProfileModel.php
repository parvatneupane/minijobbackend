<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeLancerProfileModel extends Model
{
    use HasFactory;

    protected $table = 'free_lancer_profiles';

    protected $fillable = [
        'user_id',
        'title',
        'bio',
        'experience_years',
        'hourly_rate',
        'skills',
        'location',
        'availability',
        'portfolio_url',
        'rating',
        'completed_jobs',
        'status',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'experience_years' => 'integer',
        'completed_jobs' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isAvailable()
    {
        return $this->availability === 'available';
    }

    public function isBusy()
    {
        return $this->availability === 'busy';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isBlocked()
    {
        return $this->status === 'blocked';
    }
}