<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCategoryModel extends Model
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
}