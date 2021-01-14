<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center flex-wrap justify-center md:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-5 md:mb-0">
                {{ __('Event') }}
            </h2>
            <div class="flex items-center">
                <a href="{{ route('attendees.create', \Carbon\Carbon::parse($event->date)->format('d.m.Y')) }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg mr-2">Aufrufen</a>
                <a href="{{ route('events.edit', $event) }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg mr-2">Bearbeiten</a>
                <form method="post" action="{{ route('events.destroy', $event) }}"
                      onsubmit="return confirm('Event löschen?');">
                    @method('delete')
                    @csrf
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-500 text-white font-semibold py-1 px-3 rounded-lg">Löschen
                    </button>
                </form>
            </div>
        </div>


    </x-slot>

    <div class="mt-10 max-w-6xl mx-auto px-5 pb-10">
        <div class="flex flex-wrap justify-between items-baseline">
            <div class="max-w-xl">
                <h1 class="text-2xl mb-1">{{ $event->title }}</h1>
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
                    <p class="ml-2">{{ $event->attendees()->where('type', 'child_old')->count() }}/{{ $event->max_children_old }} Kinder (2. Kl. - 6. Kl.)</p>
                </div>
                <div class="flex items-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-user">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <p class="ml-2">{{ $event->attendees()->where('type', 'child_young')->count() }}/{{ $event->max_children_young }} Kinder (3 Jahre - 1. Kl.)</p>
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
                <h2 class="text-2xl mb-1">Beschreibung</h2>
                <div class="markdown text-center">
                    {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $event->description) !!}
                </div>
            </div>
        </div>


        <div class="flex justify-between items-center mb-5 mt-16 mx-1">
            <div>
                <h2 class="text-2xl mb-3" id="attendees">Anmeldungen</h2>
                @if(count($event->attendees()->get()) > 0)
                    <div class="flex items-center flex-wrap">
                        <p class="text-lg text-gray-800 mr-2">Filter</p>
                        <div class="flex flex-wrap">
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
                   class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg flex items-center">
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <form method="post" action="{{ route('attendees.destroy', $att) }}"
                                                      onsubmit="return confirm('Anmeldung löschen?');">
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

    </div>


</x-app-layout>
