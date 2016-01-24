<?php 

namespace Queueless\Repositories;

use Queueless\Employee;
use Queueless\Organisation;

interface EmployeeRepositoryInterface
{
    /**
     * Find all employees paginated.
     *
     * @param  Queueless\Organisation $organisation
     * @param  int  $perPage
     * @return Illuminate\Database\Eloquent\Collection|\Queueless\Employee[]
     */
    public function findAllPaginatedForOrganisation($organisation, $perPage = 8);

   /**
     * Find the employee by the given id belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Employee
     */
    public function findByIdForOrganisation($id, Organisation $organisation);

    /**
     * Find the admin belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Employee
     */
    public function getAdminForOrganisation(Organisation $organisation);

    /**
     * Find the employee by the given email address from the given organisation.
     *
     * @param  int  $email
     * @param  \Queueless\Organisation  $organisation
     * @return \Queueless\Employee
     */
    public function findByEmailForOrganisation($email, Organisation $organisation);

    /**
     * Create a new employee in the database.
     *
     * @param  array $data
     * @return \Queueless\Employee
     */
    public function createForOrganisation(array $data, Organisation $organisation);
}