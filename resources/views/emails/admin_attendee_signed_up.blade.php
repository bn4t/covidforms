@component('mail::message')
# Neue Annmeldung für {{ $event->title }}

# Event Daten

Event: {{ $event->title }}

Datum: {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}


# Neue Angemeldete Teilnehmer

@component('mail::table')
| Vorname       | Nachname         | Typ | Bemerkung |
| :-------------: |:-------------:| :--------:|:-----:|
@foreach($attendees as $att)
@switch($att->type)
@case('adult')
| {{ addcslashes($att->first_name , '\_`*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\_`*[]()#+-.!') }}| Erwachsener | {{ addcslashes($att->comment , '\_`*[]()#+-.!') }} |
@break
@case('child_old')
| {{ addcslashes($att->first_name , '\_`*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\_`*[]()#+-.!') }} | Kind (2. Kl. - 6. Kl.) | {{ addcslashes($att->comment , '\_`*[]()#+-.!') }} |
@break
@case('child_young')
| {{ addcslashes($att->first_name , '\_`*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\_`*[]()#+-.!') }} | Kind (3 Jahre - 1. Kl.) | {{ addcslashes($att->comment , '\_`*[]()#+-.!') }} |
@break
@case('baby')
| {{ addcslashes($att->first_name , '\_`*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\_`*[]()#+-.!') }} | Kleinkind (0 - 3 Jahre) | {{ addcslashes($att->comment , '\_`*[]()#+-.!') }} |
@break
@endswitch
@endforeach
@endcomponent

Deine Benachrichtigungseinstellungen für diesen Event kannst du [hier]({{route('notification_settings.edit', $event)}}) ändern.

@endcomponent
