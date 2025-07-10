@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-users-cog me-2"></i>Управление пользователями
            </h2>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-id-card"></i> ID</th>
                            <th><i class="fas fa-user"></i> Имя</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-user-shield"></i> Роль</th>
                            <th><i class="fas fa-user-lock"></i> Статус</th>
                            <th><i class="fas fa-cog"></i> Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                <span class="badge bg-{{ $user->is_admin ? 'primary' : 'secondary' }}">
                                    <i class="fas fa-{{ $user->is_admin ? 'crown' : 'user' }} me-1"></i>
                                    {{ $user->is_admin ? 'Админ' : 'Пользователь' }}
                                </span>
                                </td>
                                <td>
                                <span class="badge bg-{{ $user->is_blocked ? 'danger' : 'success' }}">
                                    <i class="fas fa-{{ $user->is_blocked ? 'lock' : 'check-circle' }} me-1"></i>
                                    {{ $user->is_blocked ? 'Заблокирован' : 'Активен' }}
                                </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('admin.blockUser', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-icon btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-warning' }}"
                                                    title="{{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }}">
                                                <i class="fas fa-{{ $user->is_blocked ? 'unlock' : 'lock' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-sm btn-danger"
                                                    title="Удалить"
                                                    onclick="return confirm('Удалить пользователя {{ $user->name }}?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        .btn-icon:hover {
            transform: scale(1.1);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35em 0.65em;
            font-weight: 500;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }
    </style>
@endsection
