@component('mail::message')
# Anmeldung erfolgreich

Deine Anmeldung ist erfolgreich bei uns angekommen!

# Event Daten

Event: {{ $event->title }}

Datum: {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}


# Angemeldete Teilnehmer

@component('mail::table')
| Vorname       | Nachname         | Typ | Bemerkung |
| :-------------: |:-------------:| :--------:|:-----:|
@foreach($attendees as $att)
@switch($att->type)
@case('adult')
| {{ addcslashes($att->first_name , '\ `*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\ `*[]()#+-.!') }}| Erwachsener | {{ addcslashes($att->comment , '\ `*[]()#+-.!') }} |
@break
@case('child_old')
| {{ addcslashes($att->first_name , '\ `*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\ `*[]()#+-.!') }} | Kind (2. Kl. - 6. Kl.) | {{ addcslashes($att->comment , '\ `*[]()#+-.!') }} |
@break
@case('child_young')
| {{ addcslashes($att->first_name , '\ `*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\ `*[]()#+-.!') }} | Kind (3 Jahre - 1. Kl.) | {{ addcslashes($att->comment , '\ `*[]()#+-.!') }} |
@break
@case('baby')
| {{ addcslashes($att->first_name , '\ `*[]()#+-.!') }} | {{ addcslashes($att->last_name , '\ `*[]()#+-.!') }} | Kleinkind (0 - 3 Jahre) | {{ addcslashes($att->comment , '\ `*[]()#+-.!') }} |
@break
@endswitch
@endforeach
@endcomponent

@endcomponent
