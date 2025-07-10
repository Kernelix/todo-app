@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создание проекта</h1>

        <form method="POST" action="{{ route('projects.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название проекта</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
