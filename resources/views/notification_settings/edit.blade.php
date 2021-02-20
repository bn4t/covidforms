<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Benachrichtigungseinstellungen ändern - ').$event->title }}
            </h2>
        </div>
    </x-slot>

    <div class="mt-10 max-w-3xl mx-auto px-5 pb-10">

        @if ($errors->any())
            <div
                class="mb-12 bg-red-400 shadow-lg rounded-3xl py-4 px-6 max-w-xs mx-auto text-white text-center font-semibold">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="post" class="max-w-md mx-auto"  action="{{ route('notification_settings.store', $event) }}">
            @csrf
            <div class="flex items-center mb-3">
                <input type="checkbox" id="notify_adults" name="notify_adults" class="rounded p-2 mr-2" value="1" {{ old('notify_adults') ?? ($notify_adults == true ? 'checked="checked"' : '') }}">
                <label for="notify_adults" class="text-xl flex-1">Anmeldungen von Erwachsenen</label>
            </div>
            <div class="flex items-center mb-3">
                <input type="checkbox" id="notify_children_old" name="notify_children_old" class="rounded p-2 mr-2" value="1"  {{ old('notify_children_old') ?? ($notify_children_old == true ? 'checked="checked"' : '') }}">
                <label for="notify_children_old" class="text-xl flex-1">Anmeldungen von Kindern (2. KL. - 6. KL)</label>
            </div>
            <div class="flex items-center mb-3">
                <input type="checkbox" id="notify_children_young" name="notify_children_young" class="rounded p-2 mr-2" value="1"  {{ old('notify_children_young') ?? ($notify_children_young == true ? 'checked="checked"' : '') }}">
                <label for="notify_children_young" class="text-xl flex-1">Anmeldungen von Kindern (3 Jahre - 1. KL)</label>
            </div>
            <div class="flex items-center mb-3">
                <input type="checkbox" id="notify_babies" name="notify_babies" class="rounded p-2 mr-2" value="1"  {{ old('notify_babies') ?? ($notify_babies == true ? 'checked="checked"' : '') }}">
                <label for="notify_babies" class="text-xl flex-1">Anmeldungen von Kleinkindern (0 - 3 Jahre)</label>
            </div>
            <button class="mx-auto block bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg text-xl mt-12">Speichern</button>
        </form>

    </div>


</x-app-layout>
