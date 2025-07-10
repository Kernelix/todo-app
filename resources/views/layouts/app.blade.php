<!DOCTYPE html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --hover-color: #4f46e5;
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .nav-link {
            transition: var(--transition);
        }

        .nav-link:hover {
            transform: translateY(-2px);
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .btn-icon:hover {
            transform: scale(1.1);
        }

        .task-card {
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="/">
            <i class="fas fa-check-circle me-2"></i>ToDo Pro
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth


                    @auth
                        @if(auth()->user()->is_admin)
                            <li class="nav-item mx-2">
                                <a class="nav-link" href="{{ route('admin.users') }}">
                                    <i class="fas fa-users-cog me-1"></i> Управление пользователями
                                </a>
                            </li>
                        @endif
                    @endauth


                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('projects.index') }}" title="Проекты">
                            <i class="fas fa-folder me-1"></i>
                            <span class="d-none d-lg-inline">Проекты</span>
                        </a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('tasks.index') }}" title="Задачи">
                            <i class="fas fa-tasks me-1"></i>
                            <span class="d-none d-lg-inline">Задачи</span>
                        </a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown mx-2">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item" title="Выйти">
                                        <i class="fas fa-sign-out-alt me-2"></i> Выход
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <!-- Скрываем кнопки входа/регистрации ТОЛЬКО для главной -->
                    @unless(Request::is('/'))
                        <li class="nav-item mx-2">
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}" title="Войти">
                                <i class="fas fa-sign-in-alt"></i>
                                <span class="d-none d-lg-inline ms-1">Вход</span>
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}" title="Регистрация">
                                <i class="fas fa-user-plus"></i>
                                <span class="d-none d-lg-inline ms-1">Регистрация</span>
                            </a>
                        </li>
                    @endunless

                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="flex-shrink-0 py-4">
    <div class="container">
        @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<footer class="mt-auto bg-light py-4">
    <div class="container text-center text-muted">
        <p class="mb-0">
            <i class="fas fa-code"></i>
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Анимации при загрузке
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('show');
            }, 100);
        });
    });
</script>
</body>
</html>
