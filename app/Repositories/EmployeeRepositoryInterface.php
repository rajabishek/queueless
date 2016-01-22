<?php 

namespace Queueless\Repositories;

use Queueless\Employee;
use Queueless\Organisation;

interface EmployeeRepositoryInterface
{
    /**
     * Create a new employee in the database.
     *
     * @param  array $data
     * @return \Queueless\User
     */
    public function createForOrganisation(array $data, Organisation $organisation);
}