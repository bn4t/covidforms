<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AttendeeInputForm extends Component
{

    public $name;
    public $remaining_seats;
    public $title;

    /**
     * Create a new component instance.
     *
     * @param $name
     * @param $remainingSeats
     */
    public function __construct($name, $title, $remainingSeats)
    {
        $this->name = $name;
        $this->title = $title;
        $this->remaining_seats = $remainingSeats;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.attendee-input-form');
    }
}
