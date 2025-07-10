@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-folder me-2"></i>Доступные проекты
            </h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary rounded-circle" title="Добавить проект">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            @forelse($projects as $project)
                <div class="col-md-6 col-lg-4">
                    <div class="card project-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-folder-open text-primary me-2"></i>
                                    {{ $project['name'] }}
                                </h5>
                                <span class="badge bg-light text-dark">
                            {{ $project->tasks_count ?? 0 }} задач
                        </span>
                            </div>

                            <p class="card-text text-muted">
                                {{ $project['description'] ?? 'Описание отсутствует' }}
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 d-flex justify-content-end">
                            @if(!auth()->user()->is_admin)
                            <a href="{{ route('projects.edit', $project['id']) }}"
                               class="btn-icon btn-sm btn-outline-primary me-2"
                               title="Редактировать">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <form action="{{ route('projects.destroy', $project['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-icon btn-sm btn-outline-danger"
                                        title="Удалить"
                                        onclick="return confirm('Удалить проект и все связанные задачи?')">
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
                            <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                            <h4>Проекты не найдены</h4>
                            <p>У вас пока нет ни одного проекта</p>
                            <a href="{{ route('projects.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Создать первый проект
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .project-card {
            transition: all 0.3s ease;
            border-left: 4px solid #6366f1;
        }

        .project-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            transform: scale(1.1);
        }
    </style>
@endsection
