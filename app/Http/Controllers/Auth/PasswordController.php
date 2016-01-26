<?php

namespace Queueless\Http\Controllers\Auth;

use Password;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Queueless\Mailers\EmployeeMailer;
use Queueless\Http\Controllers\Controller;
use Queueless\Repositories\OrganisationRepositoryInterface;
use Queueless\Repositories\EmployeeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PasswordController extends Controller
{
    /**
     * Organisation Repository.
     * 
     * @var \Queueless\Repositories\OrganisationRepositoryInterface
     */
    protected $organisations;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(OrganisationRepositoryInterface $organisations)
    {
        $this->middleware('guest');

        $this->organisations = $organisations;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEmail($domain)
    {
        $organisation = $this->organisations->findByDomain($domain);
        return view('auth.password',compact('domain','organisation'));
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail($domain, Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                $message = 'An email has been sent to the specified email address. Follow the instructions in the mail to reset your password.';
                
                if($request->ajax())
                    return response()->json(['success' => true,'messages' => explode('. ',$message)]);

                flash()->message($message);
                return redirect()->back();

            case Password::INVALID_USER:

                if($request->ajax())
                    return response()->json(['success' => false,'errors' => trans($response)]);

                flash()->error(trans($response));
                return redirect()->back();
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return 'Your Password Reset Link';
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($domain, $token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }
        $organisation = $this->organisations->findByDomain($domain);
        return view('auth.reset',compact('domain','token','organisation'));
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset($domain, EmployeeRepositoryInterface $employees, Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        switch($response) 
        {
            case Password::PASSWORD_RESET:
                $message = 'Your password has been reset.';

                $organisation = $this->organisations->findByDomain($domain);
                $user = $employees->findByEmailForOrganisation($credentials['email'],$organisation);
                
                app(EmployeeMailer::class)->forUser($user)
                                          ->passwordChanged()
                                          ->queue()->deliver(); 

                flash()->success($message);
                return redirect()->route('auth.getLogin',$domain);

            default:

                flash()->error(trans($response));
                return redirect()->back()->withInput();
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
    }
}
