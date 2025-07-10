<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AdminController;

// Главная страница
Route::get('/', function () {
    return auth()->check() ? redirect('/tasks') : view('welcome');
})->name('home');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Проекты
Route::resource('projects', ProjectController::class)->middleware('auth');

// Задачи
Route::resource('tasks', TaskController::class)->middleware('auth');
Route::get('/tasks/filter/{status}', [TaskController::class, 'filter'])
    ->name('tasks.filter')
    ->middleware('auth');

// Админка
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.blockUser');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
});
