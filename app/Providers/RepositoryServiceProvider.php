<?php

namespace App\Providers;

use App\Interfaces\UrlRepositoryInterfaces;
use App\Interfaces\UserRepositoryInterfaces;
use App\Repositories\UrlRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterfaces::class, UserRepository::class);
        $this->app->bind(UrlRepositoryInterfaces::class, UrlRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
