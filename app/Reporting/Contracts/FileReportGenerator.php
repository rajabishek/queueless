<?php 

namespace Queueless\Reporting\Contracts;

use Queueless\Organisation;

interface FileReportGenerator
{
    /**
     * Set the column name to order the employee list
     *
     * @param  string  $orderby
     * @return \Queueless\Reporting\FileReportGenerator
     */
    public function orderby($orderby);

    /**
     * Set whether to order in ascending or descending.
     *
     * @param  string  $ordertype
     * @return \Queueless\Reporting\FileReportGenerator
     */
    public function ordertype($ordertype);

    /**
     * Get the employee details as a report in a file from the given organisation.
     *
     * @param  \Queueless\Organisation  $organisation
     * @return bool
     */
    public function getEmployeeDetailsForOrganisation(Organisation $Organisation);
}