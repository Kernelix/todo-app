@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-plus-circle me-2"></i>Новая задача
                            </h4>
                            <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary" title="Назад">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-1"></i>Название <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Описание
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="project_id" class="form-label">
                                        <i class="fas fa-folder me-1"></i>Проект <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('project_id') is-invalid @enderror"
                                            id="project_id" name="project_id" required>
                                        <option value="">Выберите проект</option>
                                        @foreach($projects as $project)
                                            <option value="{{ is_array($project) ? $project['id'] : $project->id }}"
                                                {{ old('project_id') == (is_array($project) ? $project['id'] : $project->id) ? 'selected' : '' }}>
                                                {{ is_array($project) ? $project['name'] : $project->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">
                                        <i class="fas fa-tasks me-1"></i>Статус <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        @foreach([
                                            'pending' => 'В ожидании',
                                            'in_progress' => 'В работе',
                                            'testing' => 'Тестирование',
                                            'review' => 'На проверке',
                                            'completed' => 'Завершено'
                                        ] as $key => $status)
                                            <option value="{{ $key }}"
                                                {{ old('status') == $key ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Создать задачу
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
