<?php 

namespace Queueless\Reporting;

use Illuminate\Contracts\Foundation\Application;
use Queueless\Reporting\Contracts\Factory as FactoryContract;
use Queueless\Reporting\Contracts\FileReportGenerator;

class FileReportGeneratorManager implements FactoryContract
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Get the default format of the report generator.
     *
     * @var string
     */
    protected $defaultFormat = 'excel';

    /**
     * The array of resolved filereport generators.
     *
     * @var array
     */
    protected $fileReportGenerators = [];

    /**
     * Create a new FileReportGeneratorManager manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get a filereport generator instance for the given format.
     *
     * @param  string  $format
     * @return \Queueless\Reporting\Contracts\FileReportGenerator
     */
    public function format($format = null)
    {
        $format = $format ?: $this->defaultFormat;

        return $this->fileReportGenerators[$format] = $this->get($format);
    }

    /**
     * Attempt to get the filereport generator from the local cache.
     *
     * @param  string  $format
     * @return \Queueless\Reporting\Contracts\FileReportGenerator
     */
    protected function get($format)
    {
        return isset($this->fileReportGenerators[$format]) ? $this->fileReportGenerators[$format] : $this->resolve($format);
    }

    /**
     * Resolve the given file report generator for the given format.
     *
     * @param  string  $format
     * @return \Queueless\Reporting\Contracts\FileReportGenerator
     */
    protected function resolve($format)
    {
        return $this->{'create'.ucfirst($format).'ReportGenerator'}();
    }

    /**
     * Create an instance of the excel report generator.
     *
     * @return \Queueless\Reporting\Contracts\FileReportGenerator
     */
    public function createExcelReportGenerator()
    {
        return $this->app->make('Queueless\Reporting\ExcelReportGenerator');
    }
}