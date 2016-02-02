<?php 

namespace Queueless\Transformers;

use Queueless\Employee;

class EmployeeTransformer extends Transformer 
{
    /**
     * Transform the given employee
     *
     * @param array $employee
     * @return array
     */
    public function transform($employee)
    {
        return [
            'id' => intval($employee['id']),
            'email' => $employee['email'],
            'fullname' => $employee['fullname'],
            'address' => $employee['address'],
            'mobile' => $employee['mobile'],
            'designation' => $employee['designation']
        ];
    }
}