<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'client_id',
        'category_id',
        'title',
        'description',
        'deadline',
        'required_skills',
        'min_experience',
        'budget',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'budget' => 'integer'
        ];
    }

    public function client()
    {
        return $this->belongsTo(
            ClientProfileModel::class,
            'client_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            TaskCategoryModel::class,
            'category_id'
        );
    }
}