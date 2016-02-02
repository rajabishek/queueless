<?php

namespace Queueless\Events\Users;

use Queueless\Organisation;
use Queueless\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewAppointmentWasRequested extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @var \Queuless\Organisation
     */
    public $organisation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['organisation.'.$this->organisation->id];
    }
}
