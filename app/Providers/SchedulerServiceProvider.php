<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
use Queueless\Services\Appointment\Scheduler;
use Queueless\Services\Appointment\QueueScheduler;

class SchedulerServiceProvider extends ServiceProvider
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
        $this->app->bind(Scheduler::class, QueueScheduler::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Scheduler::class];
    }
}
