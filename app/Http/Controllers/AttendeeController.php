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


    public function __construct()
    {
        $this->middleware('auth')->except('create', 'store');
    }

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


    public function downloadCsv(Event $event)
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=Teilnehmer-'.str_replace(' ', '-',$event->title).'-'.Carbon::parse($event->date)->format('Y-m-d').'.csv'
            , 'Expires' => '0'
            , 'Pragma' => 'public'
        ];

        $list = $event->attendees()->get(['created_at', 'first_name', 'last_name', 'email', 'type'])->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, ['Datum Anmeldung', 'Vorname', 'Nachname', 'Email', 'Typ']);

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            $i = 0;
            foreach ($list as $row) {
                if ($i > 0) {
                    $row['created_at'] = Carbon::parse($row['created_at'])->format('Y.m.d h:m:s');

                    switch ($row['type']) {
                        case 'adult':
                            $row['type'] = 'Erwachsen';
                            break;
                        case 'child_old':
                            $row['type'] = 'Kind (2. Kl. - 6. Kl.)';
                            break;
                        case 'child_young':
                            $row['type'] = 'Kind (3 Jahre - 1. Kl.)';
                            break;
                        case 'baby':
                            $row['type'] = 'Kleinkind (0 - 3 Jahre)';
                            break;
                    }
                }

                fputcsv($FH, $row);
                $i++;
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
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


        $newAttendees = [];
        foreach ($validated as $att) {
            $attendee = new Attendee;
            $attendee->event_id = $event->id;
            $attendee->first_name = $att['first_name'];
            $attendee->last_name = $att['last_name'];
            $attendee->email = $att['email'];
            $attendee->type = $att['type'];
            $attendee->save();
            array_push($newAttendees, $attendee);
        }

        Mail::to($validated[0]['email'])->send(new SignupSuccessful($newAttendees, $event));

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
