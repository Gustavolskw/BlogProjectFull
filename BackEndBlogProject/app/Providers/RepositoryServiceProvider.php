<?php

namespace App\Providers;

use App\Interfaces\CommentLikesRepositoryInterface;
use App\Interfaces\CommentRepositoryInterface;
use App\Interfaces\ThreadsRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repository\CommentLikesRepository;
use App\Repository\CommentRepository;
use App\Repository\ThreadsRepository;
use App\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ThreadsRepositoryInterface::class, ThreadsRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(CommentLikesRepositoryInterface::class, CommentLikesRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}