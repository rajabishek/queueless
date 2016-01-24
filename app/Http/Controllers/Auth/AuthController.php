<?php

namespace Queueless\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Queueless\Http\Controllers\Controller;
use Queueless\Mailers\EmployeeMailer;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Exceptions\InvalidConfirmationCodeException;
use Queueless\Exceptions\EmployeeNotFoundException;
use Queueless\Exceptions\OrganisationNotFoundException;


class AuthController extends Controller
{
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/users';

    /**
     * Auth manager instance to manage the authetication.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Employee repository instance.
     *
     * @var \Queueless\Repositories\EmployeeRepositoryInterface
     */
    protected $employees;

    /**
     * Organisation repository instance.
     *
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthManager $auth,
        EmployeeRepositoryInterface $employees,
        OrganisationRepositoryInterface $organisations)
    {
        $this->middleware('guest', ['except' => 'getLogout']);

        $this->auth = $auth;
        $this->employees = $employees;
        $this->organisations = $organisations;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Register the given organisation.
     *
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    protected function registerOrganisation(Request $request)
    {
        $data = $request->all();
        $data['confirmation_code'] = str_random(30);
        $organisation = $this->organisations->create($data);
        
  
        $data['designation'] = 'Admin';   
        $user = $this->employees->createForOrganisation($data,$organisation);

        app(EmployeeMailer::class)->forUser($user)
                     ->emailVerification()
                     ->queue()->deliver();
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validate($request, [
            'email'     => 'required|email|max:255|unique:employees',
            'fullname'  => 'required|max:255',
            'password'  => 'required|confirmed|min:6',
            'name'      => 'required|max:255',
            'domain'    => 'required|alpha_num|max:20|unique:organisations,domain'
        ]);

        $this->registerOrganisation($request);

        flash()->message('Thanks for registering with us. Please check your email.');
        return redirect()->back();
    }

    /**
     * Confirm the confirmation code and login the registered user
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm($confirmationCode)
    {
        try{
            if(!$confirmationCode)
                throw new InvalidConfirmationCodeException;

            $organisation = $this->organisations->findByConfirmationCode($confirmationCode);

            $organisation->confirmed = true;
            $organisation->confirmation_code = null;
            $organisation->save();

            //Email the user confirmating account creation
            $user = $this->employees->getAdminForOrganisation($organisation);
            app(EmployeeMailer::class)->forUser($user)
                                      ->welcome()
                                      ->queue()->deliver(); 

            flash()->message('You have successfully verified your account. Please log in.');
            return redirect()->route('auth.getLogin',$organisation->domain);
        }
        catch(InvalidConfirmationCodeException $e)
        {
            return view('errors.403'); //404
        }
        catch(EmployeeNotFoundException $e)
        {
            return view('errors.503'); //500
        }
        catch(OrganisationNotFoundException $e)
        {
            return view('errors.503'); //500
        }
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin($domain)
    {
        $organisation = $this->organisations->findByDomain($domain);
        return view('auth.login',compact('domain','organisation'));
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin($domain, Request $request)
    {
        $this->validate($request, [
            'email' => 'required', 
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember'))) 
        {
            return redirect()->intended(route('admin.users.index', $domain));
        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'These credentials do not match our records.']);      
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout($domain)
    {
        $this->auth->logout();
        return redirect()->route('auth.getLogin', $domain);
    }

    /**
     * Present a form for resending the account verification email to the admin of the organisation.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResend()
    {
        return view('auth.resend');
    }

    /**
     * Resend the account verification email to the admin of the organisation.
     *
     * @return \Illuminate\Http\Response
     */
    public function postResend(Request $request)
    {
        $this->validate($request, [
            'domain' => 'required|alpha_num|max:20|exists:organisations,domain',
            'email'  => 'required|email',
        ]);
        
        try
        {
            $organisation = $this->organisations->findByDomain($request->get('domain'));
            $user = $this->employees->findByEmailForOrganisation($request->get('email'), $organisation);

            if($organisation->confirmed)
            {
                flash()->success("{$organisation->name}'s account is already verified, you may log in here.");
                return redirect()->route('auth.getLogin',$organisation->domain);
            }

            if($user->hasRole('Admin'))
            {
                //Email the user
                app(EmployeeMailer::class)->forUser($user)
                                          ->emailVerification()
                                          ->queue()->deliver(); 
                
                flash()->success('The verification email has been resent. Please check your email.');
                return redirect()->back();
            }
            else 
                throw new EmployeeNotFoundException;
        }
        catch(OrganisationNotFoundException $e)
        {
            flash()->error('The provided domain does not exist.');
            return redirect()->back()->withInput();
        }
        catch(EmployeeNotFoundException $e)
        {
            flash()->error("The provided email address is not the same as {$organisation->name}'s admin.");
            return redirect()->back()->withInput();
        }
    }
}