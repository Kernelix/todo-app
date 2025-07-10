<?php

namespace App\Providers;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ProjectRepositoryInterface::class,
            ProjectRepository::class
        );

        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
