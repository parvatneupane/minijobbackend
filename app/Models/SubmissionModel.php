<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionModel extends Model
{
    use HasFactory;

    protected $table = 'submissions';

    protected $fillable = [
        'milestone_id',
        'free_lancer_id',
        'submission_file',
        'submission_description',
        'status'
    ];

    public function milestone()
    {
        return $this->belongsTo(
            MilestoneModel::class,
            'milestone_id'
        );
    }

    public function freelancer()
    {
        return $this->belongsTo(
            FreeLancerProfileModel::class,
            'free_lancer_id'
        );
    }
}