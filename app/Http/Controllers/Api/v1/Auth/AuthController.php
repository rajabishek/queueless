<?php

namespace Queueless\Http\Controllers\Api\v1\Auth;

use Exception;
use Queueless\Employee;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Queueless\Http\Controllers\ApiController;
use Queueless\Exceptions\ValidationException;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Transformers\EmployeeTransformer;

class AuthController extends ApiController
{
    /**
     * Employee repository.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * Create a new AuthController instance.
     *
     * @param \Queueless\Repositories\EmployeeRepositoryInterface $tasks
     * @return void
     */
    function __construct(EmployeeRepositoryInterface $employees)
    {
        $this->middleware('jwt.auth', ['only' => ['validateToken','getEmployee']]);

        $this->employees = $employees;
    }

    /**
     * Get the authenticated employee.
     *
     * @param \Queueless\Transformers\EmployeeTransformer $employeeTransformer
     * @return \Illuminate\Http\Response
     */
    public function getEmployee(EmployeeTransformer $employeeTransformer)
    {
        $employee = JWTAuth::parseToken()->authenticate();

        return $this->respondWithSuccess($employeeTransformer->transform($employee->toArray()));
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        try
        {
            $this->validate($request, [
                'email' => 'required|email|max:255', 
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials))
                    return $this->respondUnauthorizedRequest($this->getFailedLoginMessage());
    
            return $this->respondWithSuccess(compact('token'));
        }
        catch(ValidationException $e)
        {
            return $this->respondUnprocessableEntity($e->getErrors()->all());
        }
        catch (JWTException $e)
        {
            return $this->respondInternalError('Sorry could not create a token.');
        }
        catch(Exception $e)
        {
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

    /**
     * Validate the given json web token.
     *
     * @return \Illuminate\Http\Response
     */
    public function validateToken() 
    {
        // jwt.refresh should have already authenticated this token
        return $this->respondWithSuccess();
    }

    /**
     * Handle a registration request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        try
        {
            $this->validate($request, [
                'email' => 'required|max:255',
                'email' => 'required|email|max:255|unique:employees',
                'password' => 'required|confirmed|min:4',
            ]);

            $data = $request->only('name','email','password');
            $employee = $this->employees->create($data);
            
            $token = JWTAuth::fromUser($employee);
            return $this->respondWithSuccess(compact('token'));
        }
        catch(ValidationException $e)
        {
            return $this->respondUnprocessableEntity($e->getErrors()->all());
        }
        catch(Exception $e)
        {
            return $this->respondInternalError();
        }
    }
}