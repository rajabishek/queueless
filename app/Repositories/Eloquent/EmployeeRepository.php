<?php

namespace Queueless\Repositories\Eloquent;

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
     * Create a new employee in the database.
     *
     * @param  array $data
     * @return \Helpsmile\User
     */
    public function createForOrganisation(array $data, Organisation $organisation)
    {
        $employee = $this->getNew();

        $employee->email        = $data['email'];
        $employee->fullname     = $data['fullname'];
        $employee->password     = $this->hasher->make($data['password']);
        $employee->designation  = $data['designation'];
        
        if(isset($data['mobile']) && $data['mobile'])
            $employee->mobile  = $data['mobile'];

        if(isset($data['address']) && $data['address'])
            $employee->address  = $data['address'];
        
        $organisation->users()->save($employee);

        // $role = Role::where('name',$data['designation'])->first();
        // $employee->attachRole($role);

        return $employee;
    }
}
