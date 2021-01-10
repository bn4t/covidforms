@component('mail::message')
# Anmeldung erfolgreich

Deine Anmeldung ist erfolgreich bei uns angekommen!

# Event Daten

Event: {{ $event->title }}

Datum: {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}


# Angemeldete Teilnehmer

@component('mail::table')
| Vorname       | Nachname         | Typ |
| :-------------: |:-------------:| :--------:|
@foreach($attendees as $att)
@switch($att->type)
@case('adult')
| {{ $att->first_name }} | {{ $att->last_name }} | Erwachsener |
@break
@case('child_old')
| {{ $att->first_name }} | {{ $att->last_name }} | Kind (2. Kl. - 6. Kl.) |
@break
@case('child_young')
| {{ $att->first_name }} | {{ $att->last_name }} | Kind (3 Jahre - 1. Kl.) |
@break
@case('baby')
| {{ $att->first_name }} | {{ $att->last_name }} | Kleinkind (0 - 3 Jahre) |
@break
@endswitch
@endforeach
@endcomponent

@endcomponent
