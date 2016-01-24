<?php

namespace Queueless\Reporting\Contracts;

interface Factory
{
    /**
     * Get a file report generator implementation.
     *
     * @param  string  $format
     * @return \Queueless\Reporting\Contracts\FileReportGenerator
     */
    public function format($format = null);
}
