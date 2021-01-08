<?php

namespace App\Http\Controllers;

use App\Mail\SignupSuccessful;
use App\Models\Attendee;
use App\Models\AttendeeSeat;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use function Psy\debug;

class AttendeeController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @param $date
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create($date)
    {

        $datetime = Carbon::parse($date)->format('Y-m-d');

        $ev = Event::where('date', $datetime)->first();
        if ($ev == null) {
            abort(404);
        }

        return view('attendees.create', [
                'event' => $ev,
                'remaining_adults' => $ev->remainingAdultSeats(),
                'remaining_children_old' => $ev->remainingChildrenOldSeats(),
                'remaining_children_young' => $ev->remainingChildrenYoungSeats(),
                'remaining_babies' => $ev->remainingBabySeats()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function store(Request $request, Event $event)
    {

        $validated = $request->validate(
            [
                '*.first_name' => 'required|max:255',
                '*.last_name' => 'required|max:255',
                '*.email' => 'required|max:255|email',
                '*.type' => 'required|in:adult,child_old,child_young,baby',
            ]);

        if (sizeof($validated) == 0) {
            abort(406);
        }

        $adults = 0;
        $children_old = 0;
        $children_young = 0;
        $babies = 0;

        foreach ($validated as $att) {
            switch ($att['type']) {
                case 'adult':
                    $adults++;
                    break;
                case 'child_old':
                    $children_old++;
                    break;
                case 'child_young':
                    $children_young++;
                    break;
                case 'baby':
                    $babies++;
                    break;
            }
        }

        if ($adults > $event->remainingAdultSeats()) {
           abort(400);
        }
        if ($children_old > $event->remainingChildrenOldSeats()) {
            abort(400);
        }
        if ($children_young > $event->remainingChildrenYoungSeats()) {
            abort(400);
        }
        if ($babies > $event->remainingBabySeats()) {
            abort(400);
        }


        foreach ($validated as $att) {
            $attendee = new Attendee;
            $attendee->event_id = $event->id;
            $attendee->first_name = $att['first_name'];
            $attendee->last_name = $att['last_name'];
            $attendee->email = $att['email'];
            $attendee->type = $att['type'];
            $attendee->save();
        }

        Mail::to($validated[0]['email'])->send(new SignupSuccessful($validated, $event));

        return view('attendees.create', ['success' => true, 'event' => $event]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Attendee $attendee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendee $attendee)
    {
        $attendee->delete();
        return redirect(route('events.show', $attendee->event_id));
    }
}
