<?php

namespace App\Http\Controllers;

use App\Mail\AdminAttendeeSignedUp;
use App\Mail\SignupSuccessful;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\EventNotificationSetting;
use App\Models\User;
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

        // try parsing the date
        $datetime = null;
        try {
            $datetime = Carbon::parse($date);
        } catch (Exception $e) {
            return view('events.404');
        }

        // prevent signups after the event already happened
        // we need to sub a day from the current date to allow guests to still signup on the day of the event
        if (Carbon::now()->subDay()->isAfter($datetime)) {
            return view('events.expired');
        }

        $ev = Event::where('date', $datetime->format('Y-m-d'))->first();
        if ($ev == null) {
            return view('events.404');
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


    // toggle whether an attendee has attended an event
    public function toggleAttendance(Event $event, Attendee $attendee)
    {
        if ($attendee->attended_event == true)
        {
            $attendee->attended_event = false;
        } else {
            $attendee->attended_event = true;
        }

        $attendee->save();

        return redirect(route('events.show', $event));
    }

    public function downloadCsv(Event $event, Request $request)
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
            , 'Content-type' => 'text/csv'
            , 'Content-Disposition' => 'attachment; filename=Teilnehmer-' . str_replace(' ', '-', $event->title) . '-' . Carbon::parse($event->date)->format('Y-m-d') . '.csv'
            , 'Expires' => '0'
            , 'Pragma' => 'public'
        ];

        $att = null;
        if ($request->input('filter_type') != "") {
            $att = $event->attendees()->where('type', '=', $request->input('filter_type'));
        } else {
            $att = $event->attendees();
        }

        $list = $att->get(['created_at', 'last_name', 'first_name', 'email', 'type', 'comment', 'attended_event'])->toArray();

        # add headers for each column in the CSV download
        array_unshift($list, ['Datum Anmeldung', 'Nachname', 'Vorname', 'Email', 'Typ', 'Bemerkung', 'Anwesend']);

        $callback = function () use ($list) {
            $FH = fopen('php://output', 'w');
            $i = 0;
            foreach ($list as $row) {
                if ($i > 0) {
                    $row['created_at'] = Carbon::parse($row['created_at'])->format('Y.m.d h:m:s');

                    switch ($row['type']) {
                        case 'adult':
                            $row['type'] = 'Erwachsener';
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

                    if ($row['attended_event'] == '1') {
                       $row['attended_event'] = '✔️';
                    } else {
                        $row['attended_event'] = '❌';
                    }
                }

                fputcsv($FH, $row, ';');
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
                '*.comment' => 'max:1024'
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
            $attendee->comment = $att['comment'];
            $attendee->save();
            array_push($newAttendees, $attendee);
        }

        Mail::to($validated[0]['email'])->queue(new SignupSuccessful($newAttendees, $event));

        foreach (EventNotificationSetting::where('event_id', $event->id)->get() as $setting) {
            $att_to_notify = [];

            foreach ($newAttendees as $att) {
                if ($att->type == 'adult' && $setting->notify_adults) {
                    array_push($att_to_notify, $att);
                    continue;
                }
                if ($att->type == 'child_old' && $setting->notify_children_old) {
                    array_push($att_to_notify, $att);
                    continue;
                }
                if ($att->type == 'child_young' && $setting->notify_children_young) {
                    array_push($att_to_notify, $att);
                    continue;
                }
                if ($att->type == 'baby' && $setting->notify_babies) {
                    array_push($att_to_notify, $att);
                    continue;
                }
            }

            if (count($att_to_notify) > 0) {
                Mail::to($setting->email)->queue(new AdminAttendeeSignedUp($att_to_notify, $event));
            }
        }


        return view('attendees.create', ['success' => true, 'event' => $event]);
    }


    /*
     * Method to add an attendee from the admin interface
     *
     * */
    public function store_admin(Request $request, Event $event)
    {

        $validated = $request->validate(
            [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|max:255|email',
                'type' => 'required|in:adult,child_old,child_young,baby',
                'comment' => 'max:1024'
            ]);


        $attendee = new Attendee;
        $attendee->event_id = $event->id;
        $attendee->first_name = $validated['first_name'];
        $attendee->last_name = $validated['last_name'];
        $attendee->email = $validated['email'];
        $attendee->type = $validated['type'];
        $attendee->comment = $validated['comment'];
        $attendee->save();

        // send out relevant notification for subscribers
        foreach (EventNotificationSetting::where('event_id', $event->id)->get() as $setting) {
            if ($attendee->type == 'adult' && $setting->notify_adults) {
                Mail::to($setting->email)->queue(new AdminAttendeeSignedUp([$attendee], $event));
                continue;
            }
            if ($attendee->type == 'child_old' && $setting->notify_children_old) {
                Mail::to($setting->email)->queue(new AdminAttendeeSignedUp([$attendee], $event));
                continue;
            }
            if ($attendee->type == 'child_young' && $setting->notify_children_young) {
                Mail::to($setting->email)->queue(new AdminAttendeeSignedUp([$attendee], $event));
                continue;
            }
            if ($attendee->type == 'baby' && $setting->notify_babies) {
                Mail::to($setting->email)->queue(new AdminAttendeeSignedUp([$attendee], $event));
            }
        }

        return redirect(route('events.show', $event));
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
