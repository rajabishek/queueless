<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
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
        return [EmployeeRepositoryInterface::class, OrganisationRepositoryInterface::class];
    }
}
