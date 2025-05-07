<?php

namespace App\Providers;

use App\Interfaces\EmailRepositoryInterface;
use App\Interfaces\PasswordRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\EmailRepository;
use App\Repositories\PasswordRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\PermissionRepository;
use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\PermissionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class,RoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailRepository::class);
        // $this->app->singleton(
        //     \Illuminate\Database\QueryException::class,
        //     \App\Exceptions\Custom\Database\CustomQueryException::class
        // );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
