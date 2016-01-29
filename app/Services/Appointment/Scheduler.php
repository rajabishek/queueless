<?php 

namespace Queueless\Services\Appointment;

use Queueless\User;
use Queueless\Employee;
use Queueless\Organisation;

interface Scheduler
{
    /**
     * Set the organisation instance on the Scheduler.
     *
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function forOrganisation(Organisation $organisation);

    /**
     * Request an appointment for the given user.
     *
     * @param  \Queueless\User $user
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function requestAppointmentForUser(User $user);

    /**
     * Fix an appointment for the given employee.
     *
     * @param  \Queueless\Employee $employee
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function getNextAppointmentForEmployee(Employee $employee);
}