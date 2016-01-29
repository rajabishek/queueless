<?php

namespace Queueless\Http\Controllers;

use Illuminate\Http\Request;
use Queueless\Http\Requests;
use Queueless\Http\Controllers\Controller;
use Queueless\Repositories\UserRepositoryInterface;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Services\Appointment\Scheduler as AppointmentScheduler;

class AppointmentController extends Controller
{
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
     * Organisation repository.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * Appointment Scheduler.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $scheduler;

    /**
     * Create a new EmployeesController instance.
     *
     * @param  \Queueless\Repositories\EmployeeRepositoryInterface $employees
     * @return void
     */
    public function __construct(EmployeeRepositoryInterface $employees,
    	UserRepositoryInterface $users,
    	OrganisationRepositoryInterface $organisations,
        AppointmentScheduler $scheduler)
    {
        $this->employees = $employees;
        $this->users = $users;
        $this->organisations = $organisations;
        $this->scheduler = $scheduler;
    }

    /**
     * Handle the appointment request from an exisiting user
     *
     * @return \Illuminate\Http\Response
     */
    public function request($domain, $id, Request $request)
    {
    	try
    	{
    		$organisation = $this->organisations->findByDomain($domain);
    		$user = $this->users->findById($id);

    		$this->scheduler->forOrganisation($organisation)->requestAppointmentForUser($user);
    	}
    	catch(UserNotFoundException $e)
    	{

    	}
    	catch(OrganisationNotFoundException $e)
    	{

    	}
    }

    /**
     * Handle the appointment request from an employee
     *
     * @return \Illuminate\Http\Response
     */
    public function next($domain, $id, Request $request)
    {
    	try
    	{
    		$organisation = $this->organisations->findByDomain($domain);
    		$employee = $this->employees->findByIdForOrganisation($id, $organisation);

    		$this->scheduler->forOrganisation($organisation)->getNextAppointmentForEmployee($employee);
    	}
    	catch(EmployeeNotFoundException $e)
    	{

    	}
    	catch(OrganisationNotFoundException $e)
    	{

    	}
    }
}
