<?php 

namespace Queueless\Mailers;

use Queueless\Exceptions\InvalidContactInformationException;
use Mail;
use Queueless\User;
use Subscription;

class EmployeeMailer extends Mailer
{
    /**
     * Create a new abstract mailer instance.
     *
     * @param \Queueless\User $user
     */
    public function __construct(User $user)
    {
        if(!is_object($user)){
            throw new InvalidContactInformationException("A valid user object must be provided for delivering an email !");
        }

        $this->to = $user->fullname;
        $this->email = $user->email;
        $this->organisation = $user->organisation;
        $this->data = $user->toArray();
        $this->data['organisation'] = $this->organisation->toArray();
    }
}