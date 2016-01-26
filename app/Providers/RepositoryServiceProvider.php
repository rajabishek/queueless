<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
use Queueless\Repositories\UserRepositoryInterface;
use Queueless\Repositories\Eloquent\UserRepository;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\Eloquent\EmployeeRepository;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Repositories\Eloquent\OrganisationRepository;
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
        $this->app->bind(EmployeeRepositoryInterface::class, EmployeeRepository::class);
        $this->app->bind(OrganisationRepositoryInterface::class, OrganisationRepository::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            UserRepositoryInterface::class, 
            EmployeeRepositoryInterface::class,
            OrganisationRepositoryInterface::class,
        ];
    }
}
