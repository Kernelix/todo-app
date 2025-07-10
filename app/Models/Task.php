<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use  SoftDeletes;

    protected $fillable = ['title', 'description', 'status', 'project_id', 'user_id'];

    public const array STATUSES = [
        'pending' => 'В ожидании',
        'in_progress' => 'В разработке',
        'testing' => 'На тестировании',
        'review' => 'На проверке',
        'completed' => 'Выполнено'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
