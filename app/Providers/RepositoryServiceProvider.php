<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
use Queueless\Repositories\UserRepositoryInterface;
use Queueless\Repositories\Eloquent\UserRepository;
use Queueless\Repositories\TaskRepositoryInterface;
use Queueless\Repositories\Eloquent\TaskRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [UserRepositoryInterface::class];
    }
}
