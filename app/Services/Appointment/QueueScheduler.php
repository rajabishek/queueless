<?php 

namespace Queueless\Services\Appointment;

use Queueless\User;
use Queueless\Employee;
use Queueless\Organisation;
use Queueless\Repositories\UserRepositoryInterface;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Exceptions\EmployeeNotFoundException;
use Queueless\Events\Users\NewAppointmentWasRequested;
use Queueless\Events\Employees\NextAppointmentWasRequested;


class QueueScheduler implements Scheduler
{
    /**
     * Organisation instance.
     *
     * @var \Queueless\Organisation
     */
    protected $organisation;

    /**
     * Organisation repository.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * Employee repository.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * User repository.
     *
     * @var \Queueless\Repositories\UserRepositoryInterface
     */
    protected $users;

    /**
     * Create a new EmployeesController instance.
     *
     * @param  \Queueless\Repositories\OrganisationRepositoryInterface $organisations
     * @param  \Queueless\Repositories\EmployeeRepositoryInterface $employees
     * @param  \Queueless\Repositories\UserRepositoryInterface $users
     * @return void
     */
    public function __construct(OrganisationRepositoryInterface $organisations,
        EmployeeRepositoryInterface $employees,
        UserRepositoryInterface $users)
    {
        $this->organisations = $organisations;
        $this->employees = $employees;
        $this->users = $users;
    }

    /**
     * Set the organisation instance on the Scheduler.
     *
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function forOrganisation(Organisation $organisation)
    {
    	$this->organisation = $organisation;

    	return $this;
    }

    /**
     * Request an appointment for the given user.
     *
     * @param  \Queueless\User $user
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function requestAppointmentForUser(User $user)
    {
    	if($this->organisationHasUsersInQueue())
            $this->addUserToQueue($user);
		else
			$this->tryAssigningAnAvailableEmployeeForUser($user);

        event(new NewAppointmentWasRequested($this->organisation));
    }

    /**
     * Fix an appointment for the given employee.
     *
     * @param  \Queueless\Employee $employee
     * @return \Queueless\Services\Appointment\Scheduler
     */
    public function getNextAppointmentForEmployee(Employee $employee)
    {
		//$this->markCurrentAppointmentAsCompleted($employee);

		if($this->organisationHasUsersInQueue())
        {
            $user = $this->getUserFromQueue();
            $this->assignEmployeeForUser($employee, $user);
        }
        else
            $this->markEmployeeAsAvailable($employee);

        event(new NextAppointmentWasRequested($this->organisation));
    }

    /**
     * Mark the given employee as available.
     *
     * @return boolean
     */
    protected function markEmployeeAsAvailable(Employee $employee)
    {
        $this->employees->finishCurrentAttendingUserForEmployee($employee);
        
        $status = 'Available';
        return $this->employees->edit($employee,compact('status'));
    }

    /**
     * Check whether there are users in the queue.
     *
     * @return boolean
     */
    protected function organisationHasUsersInQueue()
    {
        return $this->organisations->doesHaveUsersInQueue($this->organisation);
    }

    /**
     * Add the given user to the queue.
     *
     * @param \Queueless\User $user
     * @return boolean
     */
    protected function addUserToQueue(User $user)
    {
        return $this->users->addUserToQueueInOrganisation($user, $this->organisation);

        //Notify the user about successfully added them to the queue
    }

    /**
     * Get the first user from the queue.
     *
     * @param \Queueless\User $user
     * @return boolean
     */
    protected function getUserFromQueue()
    {
        return $this->users->getUserFromQueueInOrganisation($this->organisation);
    }

    /**
     * Assign the given employee to the given user.
     *
     * @param \Queueless\Employee $employee
     * @param \Queueless\User $user
     * @return boolean
     */
    protected function assignEmployeeForUser(Employee $employee, User $user)
    {
        return $this->employees->fixAppointmentWithUserForEmployee($user, $employee);

        //Notify the new user about the appointment
    }

    /**
     * Add the given user to the queue.
     *
     * @param \Queueless\User $user
     * @return boolean
     */
    protected function tryAssigningAnAvailableEmployeeForUser(User $user)
    {
        try 
        {
            $employee = $this->employees->findAvailableEmployeeFromOrganisation($this->organisation);
            return $this->assignEmployeeForUser($employee, $user);
        }   
        catch(EmployeeNotFoundException $e) 
        {
            $this->addUserToQueue($user);
        }
    }
}