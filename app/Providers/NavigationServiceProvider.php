<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
use Queueless\Services\Navigation\Builder;

class NavigationServiceProvider extends ServiceProvider
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
        $this->app['navigation.builder'] = $this->app->share(function($app) {
            return new Builder($app['config'], $app['auth']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['navigation.builder'];
    }
}
