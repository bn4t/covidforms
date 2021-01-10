<x-guest-layout>
    <div class="bg-gradient-to-r from-indigo-400 to-blue-500 h-full min-h-screen pt-5 px-3 md:pt-20 pb-10">
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
            <div
                class="mt-10 bg-green-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold"
                hidden id="success-display">
                <p>Erfolgreich angemeldet</p>
            </div>

            <div id="hide-on-success">
                <div class="mb-16 mt-10 text-center">
                    {!! \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $event->description) !!}
                </div>

                <h2 class="text-2xl text-center mb-5">Anmeldung</h2>


                <p class="mb-6 bg-red-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold"
                   id="error-display" hidden></p>

                @if($event->remainingAdultSeats() == 0 && $event->remainingChildrenOldSeats() == 0 && $event->remainingChildrenYoungSeats() == 0 &&
$event->remainingBabySeats() == 0)
                    <div
                        class="mt-10 bg-blue-500 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                        <p>Leider sind für diesen Event schon alle Plätze besetzt.</p>
                    </div>
                @else

                    <div class="w-72 mx-auto py-2 px-4 bg-blue-500 rounded-lg shadow text-white mb-5">
                        <p class="text-center text-lg">Verbleibende Plätze</p>
                        <div class="flex justify-between items-center">
                            <p>Erwachsene: </p>
                            <p class="font-semibold" id="remAdults">{{ $event->remainingAdultSeats() }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p>Kinder (2. Kl. - 6. Kl.):</p>
                            <p class="font-semibold"
                               id="remChildrenOld">{{ $event->remainingChildrenOldSeats() }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p>Kinder (3 Jahre - 1. Kl.):</p>
                            <p class="font-semibold"
                               id="remChildrenYoung">{{ $event->remainingChildrenYoungSeats() }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p>Kleinkinder (0 - 3 Jahre):</p>
                            <p class="font-semibold" id="remBabies">{{ $event->remainingBabySeats() }}</p>
                        </div>
                    </div>

                    <form onsubmit="return submitData(event);" method="post">

                        <div x-data="rootEl()" class="mb-10">

                            <div class="w-60 mx-auto mt-8 mb-3">
                                <label for="email" class="block ml-1 mb-1">Email Adresse</label>
                                <input id="email" name="email" type="email"
                                       class="py-1 px-3 rounded-lg bg-white w-full border border-blue-500 text-gray-800"
                                       required>
                            </div>


                            <div class="flex flex-wrap items-center justify-center max-w-3xl mx-auto ">
                                <template x-for="i in forms">
                                    <div x-data="attendee()"
                                         class="bg-blue-500 shadow text-white rounded-lg py-4 px-5 max-w-xs my-5 mx-4">
                                        <p class="text-2xl text-center mb-4">Teilnehmer</p>

                                        <div class="my-2">
                                            <label for="first_name" class="block ml-1 mb-1">Vorname</label>
                                            <input id="first_name" name="first_name" x-spread="auto_save"
                                                   x-model="att.first_name"
                                                   class="py-1 px-3 rounded-lg bg-white w-full border border-gray-200 text-gray-800"
                                                   required>
                                        </div>

                                        <div class="my-2">
                                            <label for="last_name" class="block ml-1 mb-1">Nachname</label>
                                            <input id="last_name" name="last_name" x-spread="auto_save"
                                                   x-model="att.last_name"
                                                   class="py-1 px-3 rounded-lg bg-white w-full border border-gray-200 text-gray-800"
                                                   required>
                                        </div>

                                        <div class="my-2">
                                            <label for="type" class="block ml-1 mb-1">Typ</label>
                                            <select name="type" id="type"
                                                    class="rounded-lg w-full border border-gray-200 text-gray-800"
                                                    x-spread="auto_save" x-model="att.att_type" required>
                                                <option value="adult">Erwachsener</option>
                                                <option value="child_old">Kind (2. Kl. - 6. Kl.)</option>
                                                <option value="child_young">Kind (3 Jahre - 1. Kl.)</option>
                                                <option value="baby">Kleinkind (0 - 3 Jahre)</option>
                                            </select>
                                        </div>


                                    </div>
                                </template>
                            </div>

                            <div class="flex items-center justify-center max-w-lg mx-auto flex-wrap">
                                <button x-on:click="addForm()" type="button"
                                        class="rounded-lg py-1 px-3 text-white font-semibold bg-blue-500 hover:bg-blue-400 flex items-center mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-user-plus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="20" y1="8" x2="20" y2="14"></line>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg>
                                    <span class="ml-1">Hinzufügen</span>
                                </button>
                                <button x-on:click="remForm()" type="button"
                                        class="rounded-lg py-1 px-3 text-white font-semibold bg-blue-500 hover:bg-blue-400 flex items-center mx-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                         viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-user-minus">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <line x1="23" y1="11" x2="17" y2="11"></line>
                                    </svg>
                                    <span class="ml-1">Entfernen</span>
                                </button>
                            </div>

                            <button type="submit"
                                    class="py-1 text-lg px-6 bg-gradient-to-r from-indigo-400 to-blue-500 hover:bg-blue-500 text-white text-center font-semibold rounded-lg block mx-auto mt-8">
                                Anmelden
                            </button>
                        </div>
                    </form>

                    <script>
                        let att_index = -1;
                        const attendees = []
                        let remainingSeats = {
                            'adults': {{$event->remainingAdultSeats()}},
                            'children_old': {{ $event->remainingChildrenOldSeats() }},
                            'children_young': {{ $event->remainingChildrenYoungSeats() }},
                            'babies': {{ $event->remainingBabySeats() }}
                        }

                        function rootEl() {
                            return {
                                forms: 1,
                                i: 0,
                                addForm: function () {
                                    if (this.forms <= 8) {
                                        this.forms++
                                    }
                                },
                                remForm: function () {
                                    if (this.forms > 1) {
                                        remLastAttendee()
                                        this.forms--
                                    }
                                }
                            }
                        }

                        function remLastAttendee() {
                            // timeout is needed to prevent race condition
                            setTimeout(function () {
                                attendees.pop()
                                att_index--
                            }, 2)
                        }

                        function attendee() {
                            att_index++
                            return {
                                id: att_index,
                                att: {
                                    first_name: "",
                                    last_name: "",
                                    att_type: "adult",
                                },
                                auto_save: {
                                    ['@click.away']() {
                                        this.store();
                                    },
                                },
                                store: function () {
                                    attendees[this.id] = {
                                        email: document.getElementById("email").value,
                                        first_name: this.att.first_name,
                                        last_name: this.att.last_name,
                                        type: this.att.att_type,
                                    }
                                }
                            }
                        }

                        function submitData(e) {
                            e.preventDefault()

                            let adults = 0;
                            let children_old = 0;
                            let children_young = 0;
                            let babies = 0;

                            attendees.forEach(function (v) {
                                switch (v.type) {
                                    case 'adult':
                                        adults++
                                        break
                                    case 'child_old':
                                        children_old++
                                        break
                                    case 'child_young':
                                        children_young++
                                        break
                                    case 'babies':
                                        babies++
                                        break
                                }
                            })

                            if (adults > remainingSeats.adults ||
                                children_old > remainingSeats.children_old ||
                                children_young > remainingSeats.children_young ||
                                babies > remainingSeats.babies) {
                                showError("Nicht genügend freie Plätze für eingetragene Teilnehmer vorhanden.")
                                return
                            }

                            axios.post('{{ route('attendees.store', $event) }}', attendees)
                                .catch(function (error) {
                                    if (error.response.status === 400) {
                                        showError("Nicht genügend freie Plätze für eingetragene Teilnehmer vorhanden.")
                                    } else {
                                        showError("Fehler beim Einreichen der Daten. Bitte erneut versuchen.")
                                        console.log(error)
                                    }
                                })
                                .then(function () {
                                    document.getElementById('success-display').hidden = false;
                                    document.getElementById('hide-on-success').hidden = true;
                                })
                            return false;
                        }

                        function showError(error) {
                            let el = document.getElementById('error-display');
                            el.hidden = false;
                            el.innerText = error;
                        }

                        function hideError() {
                            document.getElementById('error-display').hidden = true;
                        }

                        setInterval(function () {
                            axios.get('{{ route('events.remaining_seats', $event) }}')
                                .then(function (response) {
                                    remainingSeats.adults = response.data.remaining_adults;
                                    remainingSeats.children_old = response.data.remaining_children_old;
                                    remainingSeats.children_young = response.data.remaining_children_young;
                                    remainingSeats.babies = response.data.remaining_babies;

                                    document.getElementById('remAdults').innerText = remainingSeats.adults;
                                    document.getElementById('remChildrenOld').innerText = remainingSeats.children_old;
                                    document.getElementById('remChildrenYoung').innerText = remainingSeats.children_young;
                                    document.getElementById('remBabies').innerText = remainingSeats.babies;
                                })
                                .catch(function (error) {
                                    console.log(error)
                                });
                        }, 1000)

                    </script>
                @endif
            </div>
        </div>

</x-guest-layout>
