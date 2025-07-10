<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     *
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_blocked'
    ];

    /**
     *
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     *
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_blocked' => 'boolean'
    ];


    public function projects()
    {
        return $this->hasMany(Project::class);
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            if ($user->is_admin) {
                throw new \Exception('Нельзя удалить администратора');
            }
        });
    }
}
