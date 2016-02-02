<?php

namespace Queueless\Http\Controllers\Api\v1\Auth;

use Exception;
use Queueless\Employee;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Queueless\Http\Controllers\ApiController;
use Queueless\Transformers\EmployeeTransformer;
use Queueless\Exceptions\EmployeeNotFoundException;
use Queueless\Exceptions\OrganisationNotFoundException;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\OrganisationRepositoryInterface;

class AuthController extends ApiController
{
    /**
     * Employee repository.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * Organisation repository.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * The employee transformer to transform the employee data
     * 
     * @var \Queueless\Transformers\EmployeeTransformer
     */
    protected $employeeTransformer;

    /**
     * Create a new AuthController instance.
     *
     * @param \Queueless\Repositories\EmployeeRepositoryInterface $tasks
     * @return void
     */
    function __construct(EmployeeRepositoryInterface $employees, 
        OrganisationRepositoryInterface $organisations,
        EmployeeTransformer $employeeTransformer){
        
        $this->employees = $employees;
        $this->organisations = $organisations;
        $this->employeeTransformer = $employeeTransformer;
    }

    /**
     * Check whether the employee with the given email address is eligible to use web app.
     *
     * @return string
     */
    protected function checkEligibilityForLoginFromDomain($email,$domain)
    {
        try
        {
            $organisation = $this->organisations->findByDomain($domain);
            $employee = $this->employees->findByEmailForOrganisation($email,$organisation);
            
            if($employee->hasRole('Admin') || $employee->hasRole('Employee'))
            {
                return true;
            }

            return false;
        }
        catch(EmployeeNotFoundException $e)
        {
            return false;
        }
        catch(OrganisationNotFoundException $e)
        {
            return false;
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin($domain, Request $request)
    {
        try
        {   
            $credentials = $request->only(['email', 'password']);
            $this->validate($request,[
                'email'  => 'required|email',
                'password'  => 'required|min:5'
            ]);
            
            if(!$this->checkEligibilityForLoginFromDomain($credentials['email'],$domain))
                return $this->respondUnauthorizedRequest("You don't have permissions to use the application.");

            // verify the credentials and create a token for the employee
            if (! $token = JWTAuth::attempt($credentials))
                return $this->respondUnauthorizedRequest($this->getFailedLoginMessage());
            
            // if no errors are encountered we can return the generated JWT
            $employee = JWTAuth::toUser($token);
            if(!($employee->hasRole('Admin') || $employee->hasRole('Employee')))
                return $this->respondForbidden();
            
            return response()->json([
                'success' => true, 
                'token' => $token, 
                'data' => $this->employeeTransformer->transform($employee->toArray())
            ]);
                
        }
        catch(JWTException $e) 
        {
            return $this->respondInternalError('Sorry could not create a token for you.');
        }
        catch(Exception $e)
        {
            //Fall back if nothing works 
            return $this->respondInternalError();
        }
    }

    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return 'Invalid credentials.';
    }
}