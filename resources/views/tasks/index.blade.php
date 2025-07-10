@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-tasks me-2"></i>Мои задачи
            </h2>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary rounded-circle" title="Добавить задачу">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
            <div class="input-group shadow-sm">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Поиск по названию или описанию..."
                       value="{{ request('search') }}"
                       aria-label="Поиск задач">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-1"></i> Найти
                </button>
                @if(request('search'))
                    <a href="{{ route('tasks.index', ['status' => request('status')]) }}"
                       class="btn btn-outline-secondary"
                       title="Сбросить поиск">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>

        <!-- Основные фильтры (модифицировано для работы с поиском) -->
        <div class="btn-group mb-4 shadow-sm">
            <a href="{{ route('tasks.index', ['search' => request('search')]) }}"
               class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">
                <i class="fas fa-list me-1"></i> Все
            </a>
            <a href="?status=active&search={{ request('search') }}"
               class="btn btn-outline-secondary {{ request('status') === 'active' ? 'active' : '' }}">
                <i class="fas fa-spinner me-1"></i> Активные
            </a>
            <a href="?status=completed&search={{ request('search') }}"
               class="btn btn-outline-secondary {{ request('status') === 'completed' ? 'active' : '' }}">
                <i class="fas fa-check-circle me-1"></i> Завершенные
            </a>
        </div>

        <!-- Список задач -->
        <div class="row g-3">
            @forelse($tasks as $task)
                <div class="col-md-6 col-lg-4">
                    <div class="card task-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $task['title'] }}</h5>
                                <span class="badge bg-{{
                            [
                                'pending' => 'secondary',
                                'in_progress' => 'primary',
                                'testing' => 'info',
                                'review' => 'warning',
                                'completed' => 'success'
                            ][$task['status']] ?? 'secondary'
                        }}">
                            @php
                                $statusTexts = [
                                    'pending' => 'В ожидании',
                                    'in_progress' => 'В работе',
                                    'testing' => 'Тестирование',
                                    'review' => 'На проверке',
                                    'completed' => 'Завершено'
                                ];
                            @endphp
                                    {{ $statusTexts[$task['status']] ?? $task['status'] }}
                        </span>
                            </div>

                            @if(!empty($task['project_name']))
                                <p class="small text-muted mb-2">
                                    <i class="fas fa-folder me-1"></i> {{ $task['project_name'] }}
                                </p>
                            @endif

                            <p class="card-text">{{ $task['description'] ?? 'Нет описания' }}</p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end">
                            @if(!auth()->user()->is_admin)
                            <a href="{{ route('tasks.edit', $task['id']) }}" class="btn-icon btn-sm btn-outline-primary me-2" title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-sm btn-outline-danger" title="Удалить" onclick="return confirm('Удалить задачу?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-tasks fa-4x text-muted mb-4"></i>
                            <h4>Задачи не найдены</h4>
                            @if(request('status'))
                                <p>Нет задач с выбранным фильтром</p>
                            @else
                                <p>У вас пока нет задач</p>
                            @endif
                            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Создать первую задачу
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
