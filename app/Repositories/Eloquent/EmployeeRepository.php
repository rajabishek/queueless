<?php

namespace Queueless\Repositories\Eloquent;

use Queueless\User;
use Queueless\Employee;
use Queueless\Organisation;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Exceptions\EmployeeNotFoundException;
use Illuminate\Contracts\Hashing\Hasher;

class EmployeeRepository extends AbstractRepository implements EmployeeRepositoryInterface
{
    /**
     * Employee model.
     *
     * @var \Queueless\Employee
     */
    protected $model;

    /**
     * Bcrypt hasher to hash the password.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Create a new DbEmployeeRepository instance.
     *
     * @param  \Queueless\Employee  $employee
     * @return void
     */
    public function __construct(Employee $employee, Hasher $hasher)
    {
        $this->model = $employee;
        $this->hasher = $hasher;
    }

    /**
     * Find all employees paginated.
     *
     * @param  Queueless\Organisation $organisation
     * @param  int  $perPage
     * @return Illuminate\Database\Eloquent\Collection|\Queueless\Employee[]
     */
    public function findAllPaginatedForOrganisation($organisation, $perPage = 8)
    {
        return $organisation->employees()
                    ->where('designation','!=','Admin')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
    }

   /**
     * Find the employee by the given id belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Employee
     */
    public function findByIdForOrganisation($id, Organisation $organisation)
    {
        $employee = $organisation->employees()->find($id);

        if(is_null($employee))
            throw new EmployeeNotFoundException('The employee with id as "'.$id.'" does not exist!');

        return $employee;
    }

    /**
     * Find the admin belonging to the given organisation.
     *
     * @param  int  $id
     * @param  \Queueless\Organisation $organisation
     * @return \Queueless\Employee
     */
    public function getAdminForOrganisation(Organisation $organisation)
    {
        $employee = $organisation->employees()
                                ->with('organisation')
                                ->where('designation','Admin')
                                ->first();

        if(is_null($employee))
            throw new EmployeeNotFoundException("The employee with id as $id does not exist!");

        return $employee;
    }

    /**
     * Find the employee by the given email address from the given organisation.
     *
     * @param  int  $email
     * @param  \Queueless\Organisation  $organisation
     * @return \Queueless\Employee
     */
    public function findByEmailForOrganisation($email, Organisation $organisation)
    {
        $employee = $organisation->employees()->where('email',$email)->first();

        if(is_null($employee))
            throw new EmployeeNotFoundException("The employee having email as $email does not exist, for {$organisation->name}");

        return $employee;
    }

    /**
     * Find an available employee from the given organisation.
     *
     * @param  \Queueless\Organisation  $organisation
     * @return \Queueless\Employee
     */
    public function findAvailableEmployeeFromOrganisation(Organisation $organisation)
    {
        $employee = $organisation->employees()->where('status','Available')->first();
        
        if(is_null($employee))
            throw new EmployeeNotFoundException("There are no available employee in the organisation.");

        return $employee;
    }

    /**
     * Fix the appointment with the given user for the given employee.
     *
     * @param  \Queueless\User  $user
     * @param  \Queueless\Employee $employee
     * @return boolean
     */
    public function fixAppointmentWithUserForEmployee(User $user, Employee $employee)
    {
        $employee->users()->attach($user->id);
        
        $employee->status = 'Busy';
        return $employee->save();
    }

    /**
     * Create a new employee in the database.
     *
     * @param  array $data
     * @return \Queueless\Employee
     */
    public function createForOrganisation(array $data, Organisation $organisation)
    {
        $employee = $this->getNew();

        $employee->email        = $data['email'];
        $employee->fullname     = $data['fullname'];
        $employee->password     = $this->hasher->make($data['password']);
        $employee->designation  = isset($data['designation']) ? $data['designation'] : 'Employee';
        
        if(isset($data['mobile']) && $data['mobile'])
            $employee->mobile  = $data['mobile'];

        if(isset($data['address']) && $data['address'])
            $employee->address  = $data['address'];
        
        $organisation->employees()->save($employee);

        // $role = Role::where('name',$data['designation'])->first();
        // $employee->attachRole($role);

        return $employee;
    }

    /**
     * Update the employee in the database.
     *
     * @param  \Queueless\Employee $employee
     * @param  array $data
     * @return \Queueless\Employee
     */
    public function edit(Employee $employee, array $data)
    {

        //In setting page the employee is not allowed to change his email or designation
        if(isset($data['email']))
            $employee->email  = $data['email'];
        
        if(isset($data['designation']))
            $employee->designation  = $data['designation'];
        
        if(isset($data['fullname']))
            $employee->fullname  = $data['fullname'];
        
        if(isset($data['mobile']))
            $employee->mobile  = $data['mobile'];
        
        if(isset($data['address']))
            $employee->address  = $data['address'];

        if(isset($data['status']))
            $employee->status  = $data['status'];

        //Sometimes the admin can update other details apart from the password
        //Update the password only if the admin does it.
        if(isset($data['password']))
            $employee->password = $this->hasher->make($data['password']);

        $employee->save();

        return $employee;
    }
}
