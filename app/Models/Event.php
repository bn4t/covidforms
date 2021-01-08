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
        return $this->max_adults - $this->attendees()->where('type', 'adult')->count();
    }

    public function remainingChildrenOldSeats()
    {
        return $this->max_children_old - $this->attendees()->where('type', 'child_old')->count();
    }

    public function remainingChildrenYoungSeats()
    {
        return $this->max_children_young - $this->attendees()->where('type', 'child_young')->count();
    }

    public function remainingBabySeats()
    {
        return $this->max_babies - $this->attendees()->where('type', 'baby')->count();
    }


    protected $casts = [
        'date' => 'datetime',
    ];
}
