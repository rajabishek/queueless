<?php 

namespace Queueless\Mailers;

use Queueless\Employee;
use Queueless\Exceptions\InvalidContactInformationException;

class EmployeeMailer extends Mailer
{
    /**
     * Set the employee entity on the EmployeeMailer instance.
     *
     * @param \Queueless\Employee $user
     */
    public function forUser(Employee $user)
    {
        if(!is_object($user))
        {
            throw new InvalidContactInformationException('A valid employee object must be provided for delivering an email.');
        }

        $this->to = $user->fullname;
        $this->email = $user->email;
        $this->organisation = $user->organisation;
        $this->data = $user->toArray();
        $this->data['organisation'] = $this->organisation->toArray();

        return $this;
    }

    /**
     * The method that delivers account verification link to registered employee
     *
     * @return \Queueless\Mailer\UserMailer
     */
    public function emailVerification()
    {
        $this->subject = 'Confirm your Queueless Account';
        $this->view = 'auth.emails.email-verification';

        return $this;
    }

    /**
     * The method that delivers a welcome email
     *
     * @return \Queueless\Mailer\UserMailer
     */
    public function welcome()
    {
        $this->subject = 'Welcome to Queueless';
        $this->view = 'auth.emails.welcome';

        return $this;
    }

    /**
     * Notif the user after the password has changed for him
     *
     * @return \Helpsmile\Mailer\UserMailer
     */
    public function passwordChanged()
    {
        $this->subject = 'Password Changed';
        $this->view = 'auth.emails.password-changed';

        return $this;
    }
}