<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center flex-col md:flex-row flex-wrap justify-center md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-5 md:mb-0">
                {{ __('Event') }}
            </h2>
            <div class="flex flex-col items-start md:flex-row md:items-center">
                <a href="{{ route('attendees.create', $event) }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg mr-2 flex items-center my-1"
                   target="_blank">
                    <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                         stroke="currentColor" class="w-4 h-4">
                        <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span class="ml-2">Aufrufen</span>
                </a>

                <a href="{{ route('notification_settings.edit', $event) }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg mr-2 flex items-center my-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="ml-2">Benachrichtigungen</span>
                </a>
                <a href="{{ route('events.edit', $event) }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg mr-2 flex items-center my-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="w-4 h-4">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                    <span class="ml-2">Bearbeiten</span>
                </a>
                <form method="post" action="{{ route('events.destroy', $event) }}"
                      onsubmit="return confirm('Event {{ $event->title }} löschen?');">
                    @method('delete')
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-500 text-white font-semibold py-1 px-3 rounded-lg flex items-center my-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="w-4 h-4">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                        <span class="ml-2">Löschen</span>
                    </button>
                </form>
            </div>
        </div>


    </x-slot>

    <div class="mt-10 max-w-6xl mx-auto px-5 pb-32">
        <div class="flex flex-wrap justify-between items-baseline">
            <div class="max-w-xl">
                <h1 class="text-3xl mb-1">{{ $event->title }}</h1>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-calendar">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <p class="ml-2">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</p>
                </div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="ml-2">{{ $event->attendees()->where('type', 'adult')->count() }}/{{ $event->max_adults }}
                        Erwachsene</p>
                </div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="ml-2">{{ $event->attendees()->where('type', 'child_old')->count() }}
                        /{{ $event->max_children_old }} Kinder (2. Kl. - 6. Kl.)</p>
                </div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="ml-2">{{ $event->attendees()->where('type', 'child_young')->count() }}
                        /{{ $event->max_children_young }} Kinder (3 Jahre - 1. Kl.)</p>
                </div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="ml-2">{{ $event->attendees()->where('type', 'baby')->count() }}/{{ $event->max_babies }}
                        Kleinkinder (0 - 3 Jahre)</p>
                </div>
            </div>

            <div class="max-w-2xl mt-10 md:mt-0 w-full">
                <h2 class="text-3xl mb-1">Beschreibung</h2>
                <div class="markdown text-center">
                    {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $event->description) !!}
                </div>
            </div>
        </div>


        <div class="flex justify-between items-end mb-5 mt-16 mx-1">
            <div>
                <h2 class="text-3xl mb-3" id="attendees">Anmeldungen</h2>
                @if(count($event->attendees()->get()) > 0)
                    <p class="text-lg text-gray-800 mr-2">Filter</p>

                    <div class="flex items-center flex-wrap">
                        <div class="flex flex-wrap md:flex-row flex-col">
                            <a {{ $filter == 'none' ? "" : "href=".route('events.show', $event)."#attendees" }}
                               class="{{ $filter == 'none' ? "bg-gray-800 text-white" : "bg-gray-300 hover:bg-gray-200" }} py-0.5 px-2 rounded-lg mr-1 text-sm my-1">
                                Kein Filter
                            </a>

                            <a {{ $filter == 'adult' ? "" : "href=".route('events.show', $event)."?filter_type=adult#attendees" }}
                               class="{{ $filter == 'adult' ? "bg-gray-800 text-white" : "bg-gray-300 hover:bg-gray-200" }} py-0.5 px-2 rounded-lg mr-1 text-sm my-1">
                                Erwachsene
                            </a>

                            <a {{ $filter == 'child_old' ? "" : "href=".route('events.show', $event)."?filter_type=child_old#attendees" }}
                               class="{{ $filter == 'child_old' ? "bg-gray-800 text-white" : "bg-gray-300 hover:bg-gray-200" }} py-0.5 px-2 rounded-lg mr-1 text-sm my-1">Kind
                                (2. Kl. - 6. Kl.)
                            </a>

                            <a {{ $filter == 'child_young' ? "" : "href=".route('events.show', $event)."?filter_type=child_young#attendees" }}
                               class="{{ $filter == 'child_young' ? "bg-gray-800 text-white" : "bg-gray-300 hover:bg-gray-200" }} py-0.5 px-2 rounded-lg mr-1 text-sm my-1">
                                Kind (3 Jahre - 1. Kl.)
                            </a>

                            <a {{ $filter == 'baby' ? "" : "href=".route('events.show', $event)."?filter_type=baby#attendees" }}
                               class="{{ $filter == 'baby' ? "bg-gray-800 text-white" : "bg-gray-300 hover:bg-gray-200" }} py-0.5 px-2 rounded-lg mr-1 text-sm my-1">
                                Kinderhüte
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            @if(count($event->attendees()->get()) > 0)
                <a href="{{ route('attendees.download_csv', $event) }}{{$filter == 'none' ? '': '?filter_type='.$filter }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg flex items-center mb-1 md:mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-download">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span class="ml-2">Herunterladen</span>
                </a>
            @endif
        </div>
        @if(count($event->attendees()->get()) == 0)
            <div class="text-center mt-16">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x-circle text-gray-600 text-center inline-block">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <p class="text-center text-xl text-gray-600 mt-3">Noch keine Anmeldungen.</p>
            </div>

        @elseif(count($attendees) == 0)
            <div class="text-center mt-16">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x-circle text-gray-600 text-center inline-block">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <p class="text-center text-xl text-gray-600 mt-3">Keine Anmeldungen, welche dem Filter entsprechen.</p>
            </div>
        @else
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200 sortable">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nachname
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Vorname
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Typ
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bemerkung
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Anwesend
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Manage
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendees as $att)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap max-w-xs">
                                                {{ $att->last_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap max-w-xs">
                                                {{ $att->first_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap max-w-xs">
                                                {{ $att->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap max-w-xs">
                                                @switch($att->type)
                                                    @case('adult')
                                                    Erwachsener
                                                    @break
                                                    @case('child_old')
                                                    Kind (2. Kl. - 6. Kl.)
                                                    @break
                                                    @case('child_young')
                                                    Kind (3 Jahre - 1. Kl.)
                                                    @break
                                                    @case('baby')
                                                    Kleinkind (0 - 3 Jahre)
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 break-words max-w-xs">
                                                {{ $att->comment }}
                                            </td>
                                            <td class="px-6 py-4 break-words max-w-xs"
                                                sorttable_customkey="{{$att->attended_event}}">

                                                <input type="checkbox" id="attendee-{{$att->id}}-attendance"
                                                @if($att->attended_event) {{ 'checked' }} @endif>
                                            </td>

                                            <script>
                                                let checkbox_{{$att->id}} = document.getElementById('attendee-{{$att->id}}-attendance');

                                                checkbox_{{$att->id}}.addEventListener('change', (event) => {
                                                    axios.post('{{route('attendees.toggle_attendance', ['event' => $event, 'attendee' => $att])}}')
                                                        .catch(function (error) {
                                                            console.log(error);
                                                            return false;
                                                        });
                                                })
                                            </script>

                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <form method="post" action="{{ route('attendees.destroy', $att) }}"
                                                      onsubmit="return confirm('Anmeldung von {{ $att->first_name }} {{ $att->last_name }} löschen?');">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit"
                                                            class="bg-red-600 hover:bg-red-500 text-white font-semibold py-1 px-3 rounded-lg">
                                                        Löschen
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-10">
            <h3 class="text-xl">Anmeldung hinzufügen</h3>
            <p class="text-sm">Teilnehmer, welche über das admin interface angemeldet wurden, erhalten kein
                Bestätigungsmail.</p>

            @if ($errors->any())
                <div
                    class="my-6 bg-red-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/events/{{ $event->id }}/new_attendee_admin" method="post">
                @csrf
                <div class="flex flex-col md:flex-row md:items-center items-start mt-3 justify-start">
                    <div class="flex flex-col md:mr-2 my-2">
                        <label for="last_name" class="text-sm mb-1">Nachname</label>
                        <input type="text" id="last_name" name="last_name" class="w-48 flex-grow rounded-lg py-1 px-3"
                               value="{{ old('last_name') }}" required>
                    </div>
                    <div class="flex flex-col md:mx-2 my-2">
                        <label for="first_name" class="text-sm mb-1">Vorname</label>
                        <input type="text" id="first_name" name="first_name" class="w-48 flex-grow rounded-lg py-1 px-3"
                               value="{{ old('first_name') }}" required>
                    </div>
                    <div class="flex flex-col md:mx-2 my-2">
                        <label for="email" class="text-sm mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-48 flex-grow rounded-lg py-1 px-3"
                               value="{{ old('email') }}" required>
                    </div>
                    <div class="flex flex-col md:mx-2 my-2">
                        <label for="type" class="text-sm mb-1">Typ</label>
                        <select name="type" id="type"
                                class="rounded-lg w-48 text-sm" required>
                            <option value="adult">Erwachsener</option>
                            <option value="child_old">Kind (2. Kl. - 6. Kl.)</option>
                            <option value="child_young">Kind (3 Jahre - 1. Kl.)</option>
                            <option value="baby">Kleinkind (0 - 3 Jahre)</option>
                        </select>
                    </div>
                    <div class="flex flex-col md:mx-2 my-2">
                        <label for="comment" class="text-sm mb-1">Bemerkung</label>
                        <input type="text" id="comment" name="comment" class="w-48 flex-grow rounded-lg py-1 px-3"
                               value="{{ old('comment') }}">
                    </div>
                </div>
                <button type="submit"
                        class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg flex items-center my-2">
                    Speichern
                </button>
            </form>
        </div>

    </div>


</x-app-layout>
