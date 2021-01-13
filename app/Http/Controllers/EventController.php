<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('remainingSeats');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('events.index', ['events' => Event::all()->sortBy('date',SORT_DESC)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|after_or_equal:today|date',
            'description' => 'required|max:2048',
            'max_adults' => 'required|integer|min:0',
            'max_children_old' => 'required|integer|min:0',
            'max_children_young' => 'required|integer|min:0',
            'max_babies' => 'required|integer|min:0',
        ]);

        $event = new Event;
        $event->title = $validated['title'];
        $event->date = $validated['date'];
        $event->description = $validated['description'];
        $event->max_adults = $validated['max_adults'];
        $event->max_children_old = $validated['max_children_old'];
        $event->max_children_young = $validated['max_children_young'];
        $event->max_babies = $validated['max_babies'];
        $event->save();

        return redirect(route('events.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Event $event, Request $request)
    {
        $att = null;

        if ($request->input('filter_type') != "") {
            $att = $event->attendees()->where('type', '=',$request->input('filter_type'))->get();
        } else {
            $att = $event->attendees()->get();
        }

        return view('events.show', [
                'event' => $event,
                'attendees' => $att,
                'filter' => $request->input('filter_type') == "" ? 'none': $request->input('filter_type'),
                'remaining_adults' => $event->remainingAdultSeats(),
                'remaining_children_old' => $event->remainingChildrenOldSeats(),
                'remaining_children_young' => $event->remainingChildrenYoungSeats(),
                'remaining_babies' => $event->remainingBabySeats()
            ]
        );
    }

    public function remainingSeats(Event $event)
    {
        return response()->json([
            'remaining_adults' => $event->remainingAdultSeats(),
            'remaining_children_old' => $event->remainingChildrenOldSeats(),
            'remaining_children_young' => $event->remainingChildrenYoungSeats(),
            'remaining_babies' => $event->remainingBabySeats()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        return view('events.edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|after_or_equal:today|date',
            'description' => 'required|max:2048',
            'max_adults' => 'required|integer|min:0',
            'max_children_old' => 'required|integer|min:0',
            'max_children_young' => 'required|integer|min:0',
            'max_babies' => 'required|integer|min:0',
        ]);

        // TODO: make sure events can't be specified on duplicate dates

        $event->title = $validated['title'];
        $event->date = $validated['date'];
        $event->description = $validated['description'];
        $event->max_adults = $validated['max_adults'];
        $event->max_children_old = $validated['max_children_old'];
        $event->max_children_young = $validated['max_children_young'];
        $event->max_babies = $validated['max_babies'];
        $event->update();

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect(route('events.index'));
    }
}
