<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCategoriesModel extends Model
{
    use HasFactory;

    protected $table = 'task_categories';

    protected $fillable = [
        'name',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer'
        ];
    }

    public function freelancers()
{
    return $this->belongsToMany(
        FreeLancerProfileModel::class,
        'free_lancer_profile_task_category',
        'task_category_id',
        'free_lancer_profile_id'
    );
}

    public function tasks()
    {
        return $this->hasMany(TaskModel::class, 'category_id');
    }


}