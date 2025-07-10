@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-edit me-2"></i>Редактирование проекта
                            </h4>
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-secondary" title="Назад">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.update', $project['id']) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-heading me-1"></i>Название проекта <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $project['name']) }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Описание
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description', $project['description'] ?? '') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Сохранить изменения
                                </button>

                                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Отмена
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 10px;
        }
        .form-control, .form-select {
            border-radius: 8px;
        }
        .btn {
            border-radius: 8px;
        }
    </style>
@endsection
