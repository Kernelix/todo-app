<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Список всех пользователей
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // Блокировка/разблокировка пользователя
    public function blockUser($id)
    {
        $user = User::findOrFail($id);

        // Нельзя заблокировать себя
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Вы не можете заблокировать себя');
        }

        $wasBlocked = $user->is_blocked;
        $user->update(['is_blocked' => !$user->is_blocked]);

        if (!$wasBlocked) {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }

        return back()->with('success', 'Статус пользователя обновлен');
    }

    // Удаление пользователя
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Пользователь удален');
    }
}
