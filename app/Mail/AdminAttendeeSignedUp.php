<?php

namespace App\Mail;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAttendeeSignedUp extends Mailable
{
    use Queueable, SerializesModels;


    public $attendees;
    public $event;

    /**
     * Create a new message instance.
     *
     * @param Attendee[] $attendees
     */
    public function __construct(array $attendees, Event $event)
    {
        $this->attendees = $attendees;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@chrischona-koelliken.ch')
            ->subject('Neue Anmeldung für '.$this->event->title)
            ->markdown('emails.admin_attendee_signed_up', [
                'attendees' => $this->attendees,
                'event' => $this->event,
            ]);
    }
}
