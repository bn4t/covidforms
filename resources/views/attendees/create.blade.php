<x-guest-layout>
    <div class="bg-gradient-to-r from-indigo-400 to-blue-500 h-full min-h-screen pt-5 md:pt-20">
        <div class="rounded-3xl shadow-lg p-9 bg-white max-w-4xl mx-auto">
            <h1 class="text-4xl text-center">{{ $event->title }}</h1>
            <p class="mt-1 text-gray-700 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-calendar">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span class="ml-1">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</span>
            </p>
            @if($success ?? '')
                <div
                    class="mt-10 bg-green-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                    <p>Erfolgreich angemeldet</p>
                </div>
            @else

                <div
                    class="my-20 text-center">{!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $event->description) !!}</div>

                <h2 class="text-2xl text-center mb-4">Anmeldung</h2>

                @if ($errors->any())
                    <div
                        class="mb-6 bg-red-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($remaining_adults == 0 && $remaining_lions == 0 && $remaining_kangaroos == 0 && $remaining_babies == 0)
                    <div
                        class="mt-10 bg-blue-500 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                        <p>Leider sind für diesen Event schon alle Plätze besetzt.</p>
                    </div>
                @else
                    <form action="{{ route('attendees.store') }}" class="max-w-xs mx-auto" method="post">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="mb-6">
                            <label for="name" class="block mb-1 flex justify-between items-center px-1">
                                <span>Name</span>
                            </label>
                            <input type="text" id="name" name="name" class="py-1 px-3 rounded-xl bg-white w-full"
                                   required>
                        </div>

                        <div class="mb-14">
                            <label for="email" class="block mb-1 flex justify-between items-center px-1">
                                <span>Email Adresse</span>
                            </label>
                            <input type="email" id="email" name="email" class="py-1 px-3 rounded-xl bg-white w-full"
                                   required>
                        </div>

                        <div class="mb-6">
                            <x-attendee-input-form name="adults" title="Erwachsene"
                                                   remaining_seats="{{ $remaining_adults }}"/>
                        </div>

                        <div class="mb-6">
                            <x-attendee-input-form name="lions" title="Kindertreff (2. Kl. - 6. Kl.)"
                                                   remaining_seats="{{ $remaining_lions }}"/>
                        </div>

                        <div class="mb-6">
                            <x-attendee-input-form name="kangaroos" title="Kindertreff (3 Jahre - 1. Kl.)"
                                                   remaining_seats="{{ $remaining_kangaroos }}"/>
                        </div>

                        <div class="mb-6">
                            <x-attendee-input-form name="babies" title="Kinderhüte (0 - 3 Jahre)"
                                                   remaining_seats="{{ $remaining_babies }}"/>
                        </div>
                        <button type="submit"
                                class="block mx-auto py-1 px-3 rounded-3xl bg-blue-500 hover:bg-blue-400 text-white font-semibold mt-10">
                            Anmelden
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

</x-guest-layout>
