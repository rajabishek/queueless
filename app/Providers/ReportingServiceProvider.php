<?php

namespace Queueless\Providers;

use Illuminate\Support\ServiceProvider;
use Queueless\Reporting\FileReportGeneratorManager;

class ReportingServiceProvider extends ServiceProvider
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
        $this->registerManager();

        $this->app->singleton('report.generator.excel', function($app) {
            return $app['report.generator.file']->format('excel');
        });
    }

    /**
     * Register the file report generator manager.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('report.generator.file', function($app) {
            return new FileReportGeneratorManager($app);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['report.generator.file', 'report.generator.file'];
    }
}
