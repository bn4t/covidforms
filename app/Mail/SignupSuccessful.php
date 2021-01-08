<?php

namespace App\Mail;

use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupSuccessful extends Mailable
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
            ->subject('Anmeldung für '.$this->event->title." erfolgreich")
            ->markdown('emails.signup_successful', [
                'attendees' => $this->attendees,
                'event' => $this->event,
            ]);
    }
}
