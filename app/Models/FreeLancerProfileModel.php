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
        'profile_image',
        'bio',
        'experience_years',
        'hourly_rate',
        'location',
        'skills',
        'rating',
        'availability',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'experience_years' => 'integer',
            'hourly_rate' => 'decimal:2',
            'rating' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}