<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // Все могут просматривать список задач
    }

    public function view(User $user, Task $task)
    {
        return true; // Все могут просматривать конкретную задачу
    }

    public function create(User $user)
    {
        return true; // Все могут создавать задачи
    }

    public function update(User $user, Task $task)
    {
        // Только автор или админ может редактировать
        return $user->id === $task->user_id || $user->is_admin;
    }

    public function delete(User $user, Task $task)
    {
        // Только автор или админ может удалять
        return $user->id === $task->user_id || $user->is_admin;
    }
}
