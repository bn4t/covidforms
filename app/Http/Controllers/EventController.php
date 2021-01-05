<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show');
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
            'date' => 'required|unique:events|after_or_equal:today|date',
            'description' => 'required|max:2048',
            'max_adults' => 'required|integer|min:0',
            'max_lions' => 'required|integer|min:0',
            'max_kangaroos' => 'required|integer|min:0',
            'max_babies' => 'required|integer|min:0',
        ]);

        $event = new Event;
        $event->title = $validated['title'];
        $event->date = $validated['date'];
        $event->description = $validated['description'];
        $event->max_adults = $validated['max_adults'];
        $event->max_lions = $validated['max_lions'];
        $event->max_kangaroos = $validated['max_kangaroos'];
        $event->max_babies = $validated['max_babies'];
        $event->save();

        return redirect(route('events.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $remAdults = $event->max_adults - $event->attendees()->sum('adults');
        $remLions = $event->max_lions - $event->attendees()->sum('lions');
        $remKangaroos = $event->max_kangaroos - $event->attendees()->sum('kangaroos');
        $remBabies = $event->max_babies - $event->attendees()->sum('babies');

        return view('events.show', [
                'event' => $event,
                'remaining_adults' => $remAdults,
                'remaining_lions' => $remLions,
                'remaining_kangaroos' => $remKangaroos,
                'remaining_babies' => $remBabies
            ]
        );
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
            'max_lions' => 'required|integer|min:0',
            'max_kangaroos' => 'required|integer|min:0',
            'max_babies' => 'required|integer|min:0',
        ]);

        // TODO: make sure events can't be specified on duplicate dates

        $event->title = $validated['title'];
        $event->date = $validated['date'];
        $event->description = $validated['description'];
        $event->max_adults = $validated['max_adults'];
        $event->max_lions = $validated['max_lions'];
        $event->max_kangaroos = $validated['max_kangaroos'];
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
