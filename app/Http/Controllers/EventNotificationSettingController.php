<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventNotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventNotificationSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function edit(Event $event)
    {
        $notify_adults = false;
        $notify_children_old = false;
        $notify_children_young = false;
        $notify_babies = false;

        if (EventNotificationSetting::all()->where('email', Auth::user()->email)->count() > 0) {
            $setting = EventNotificationSetting::all()->where('email', Auth::user()->email)->first();
            $notify_adults = $setting->notify_adults;
            $notify_children_old = $setting->notify_children_old;
            $notify_children_young = $setting->notify_children_young;
            $notify_babies = $setting->notify_babies;
        }

        return view('notification_settings.edit', [
            'event' => $event,
            'notify_adults' => $notify_adults,
            'notify_children_old' => $notify_children_old,
            'notify_children_young' => $notify_children_young,
            'notify_babies' => $notify_babies
        ]);
    }

    public function store(Event $event, Request $request)
    {
        $validated = $request->validate([
            'notify_adults' => 'boolean',
            'notify_children_old' => 'boolean',
            'notify_children_young' => 'boolean',
            'notify_babies' => 'boolean',
        ]);

        if (EventNotificationSetting::all()->where('email', Auth::user()->email)->count() > 0) {
            $setting = EventNotificationSetting::all()->where('email', Auth::user()->email)->first();
            $setting->email = Auth::user()->email;
            $setting->event_id = $event->id;
            $setting->notify_adults = $validated['notify_adults'] ?? false;;
            $setting->notify_children_old = $validated['notify_children_old'] ?? false;;
            $setting->notify_children_young = $validated['notify_children_young'] ?? false;;
            $setting->notify_babies = $validated['notify_babies'] ?? false;;
            $setting->save();
        } else {
            $setting = new EventNotificationSetting;
            $setting->email = Auth::user()->email;
            $setting->event_id = $event->id;
            $setting->notify_adults = $validated['notify_adults'] ?? false;
            $setting->notify_children_old = $validated['notify_children_old'] ?? false;;
            $setting->notify_children_young = $validated['notify_children_young'] ?? false;;
            $setting->notify_babies = $validated['notify_babies'] ?? false;;
            $setting->save();
        }

        return redirect(route('events.show', $event));
    }

}
