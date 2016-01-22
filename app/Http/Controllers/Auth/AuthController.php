<?php

namespace Queueless\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Queueless\Http\Controllers\Controller;
use Queueless\Mailers\EmployeeMailer;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Queueless\Repositories\OrganisationRepositoryInterface;

class AuthController extends Controller
{
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/employees';

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
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required', 
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors(['username' => 'These credentials do not match our records.']);      
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}