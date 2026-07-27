<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
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
            UserModel::class,
            'user_id'
        );
    }
    
    public function user()
    {
        return $this->belongsTo(
            UserModel::class,
            'user_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            TaskCategoriesModel::class,
            'category_id'
        );
    }

    public function contracts()
{
    return $this->hasOne(
        ContractModel::class,
        'task_id'
    );
}

public function proposals()
{
    return $this->hasMany(
        ProposalModel::class,
        'task_id'
    );

}

}