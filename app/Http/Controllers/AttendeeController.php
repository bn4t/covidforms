<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\AttendeeSeat;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'remaining_lions' => $ev->remainingLionSeats(),
                'remaining_kangaroos' => $ev->remainingKangarooSeats(),
                'remaining_babies' => $ev->remainingBabySeats()
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ev = Event::where('id', $request['event_id'])->first();
        if ($ev == null) {
            abort(404);
        }

        $validated = $request->validate(
            [
                'name' => 'required|max:255',
                'email' => 'required|max:255|email',
                'adults' => 'integer|min:0|max:' . strval($ev->remainingAdultSeats()),
                'lions' => 'integer|min:0|max:' . strval($ev->remainingLionSeats()),
                'kangaroos' => 'integer|min:0|max:' . strval($ev->remainingKangarooSeats()),
                'babies' => 'integer|min:0|max:' . strval($ev->remainingBabySeats()),
            ]);

        if (($validated['adults'] ?? 0) == 0 &&
            ($validated['lions'] ?? 0) == 0 &&
            ($validated['kangaroos'] ?? 0) == 0 &&
            ($validated['babies'] ?? 0) == 0) {
            abort(403); // TODO: error message
        }

        $att = new Attendee;
        $att->event_id = $ev->id;
        $att->name = $validated['name'];
        $att->email = $validated['email'];
        $att->adults = $validated['adults'] ?? 0;
        $att->lions = $validated['lions'] ?? 0;
        $att->kangaroos = $validated['kangaroos'] ?? 0;
        $att->babies = $validated['babies'] ?? 0;
        $att->save();

        return view('attendees.create', ['success' => true, 'event' => $ev]);
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
