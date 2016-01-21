<?php 

namespace Queueless\Mailers;

use Queueless\Exceptions\InvalidContactInformationException;
use Queueless\Models\Organisation;
use Illuminate\Contracts\Mail\Mailer;

abstract class Mailer
{
    /**
     * The organisation that the use belongs to.
     *
     * @var \Queueless\Models\Organisation
     */
    protected $organisation;

    /**
     * The name of the person to send the email to
     *
     * @var string
     */
    protected $to;

    /**
     * The email of the person to send the email to
     *
     * @var string
     */
    protected $email;

    /**
     * The subject of the email
     *
     * @var string
     */
    protected $subject;

    /**
     * The view representing the email content
     *
     * @var string
     */
    protected $view;

    /**
     * The data to be passed to the view
     *
     * @var string
     */
    protected $data;

    /**
     * Additional options that can be passed to the subclass
     *
     * @var Closure
     */
    protected $options;

    /**
     * Indicates where the mail job should be queued or not.
     *
     * @var boolean
     */
    protected $queue = false;

    /**
     * The method make sures that the mail job is queued
     *
     * @return boolean
     */
    public function queue()
    {
        $this->queue = true;
        return $this;
    }

    /**
     * The method that delivers the email
     *
     * @return boolean
     */
    public function deliver(Mailer $mailer)
    {
        if(! $this->queue){

            return $mailer->send($this->view,$this->data,function($message){
                $message->to($this->email,$this->to)->subject($this->subject);
                
                if(is_callable($this->options)){
                    call_user_func($this->options,$message);
                }
            });
        }

        $email = $this->email;
        $to = $this->to;
        $subject = $this->subject;
        $options = $this->options;

        return $mailer->queue($this->view,$this->data,function($message) use($email,$to,$subject,$options){
            $message->to($email,$to)->subject($subject);
            
            if(is_callable($options)){
                call_user_func($options,$message);
            }
        }); 
    }
}