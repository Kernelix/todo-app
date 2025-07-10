<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): true
    {
        return true; // Все могут просматривать список проектов
    }

    public function view(User $user, Project $project): true
    {
        return true; // Все могут просматривать конкретный проект
    }

    public function create(User $user): true
    {
        return true; // Все могут создавать проекты
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id || $user->is_admin;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id || $user->is_admin;
    }
}
