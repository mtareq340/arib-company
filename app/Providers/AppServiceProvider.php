<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\EmployeeRepositoryInterface;
use App\Repositories\EmployeeRepository;
use App\Repositories\DepartmentRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TaskRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(DepartmentRepositoryInterface::class, DepartmentRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);

    }

    public function boot()
    {
        //
    }
}
