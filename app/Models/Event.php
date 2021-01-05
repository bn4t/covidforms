<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    public function remainingAdultSeats()
    {
        return $this->max_adults - $this->attendees()->sum('adults');
    }

    public function remainingLionSeats()
    {
        return $this->max_lions - $this->attendees()->sum('lions');
    }

    public function remainingKangarooSeats()
    {
        return $this->max_kangaroos - $this->attendees()->sum('kangaroos');
    }

    public function remainingBabySeats()
    {
        return $this->max_babies - $this->attendees()->sum('babies');
    }


    protected $casts = [
        'date' => 'datetime',
    ];
}
