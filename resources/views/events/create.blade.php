<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event erstellen') }}
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


        <form method="post" action="{{ route('events.store') }}">
            @csrf
            <div class="flex items-center mb-10 flex-wrap">
                <label for="title" class="text-xl flex-1">Titel</label>
                <input type="text" id="title" name="title" class="w-full md:w-auto flex-grow rounded-lg py-1 px-3"
                       value="{{ old('title') }}" required>
            </div>
            <div class="flex items-center mb-10 flex-wrap">
                <label for="date" class="text-2xl flex-1 mr-3 md:mr-0">Datum</label>
                <input style="width: 15rem;" type="date" id="date" name="date" class="w-full md:w-auto flex-grow rounded-lg py-1 px-3"
                       value="{{ old('date') }}" required>
            </div>
            <div class="flex items-baseline mb-10 flex-wrap">
                <label for="description" class="text-xl flex-1">Beschreibung</label>
                <textarea id="description" name="description" cols="18" rows="6" class="w-full md:w-auto flex-grow rounded-lg py-1 px-3"
                          required>{{ old('description') }}</textarea>
            </div>
            <div class="flex items-center mb-10 flex-wrap">
                <label for="max_adults" class="text-xl flex-1">Max. Erwachsene</label>
                <input type="number" min="0" id="max_adults" name="max_adults" class="w-full md:w-auto flex-grow rounded-lg py-1 px-3"
                       value="{{ old('max_adults') }}"
                       required>
            </div>
            <div class="flex items-center mb-10 flex-wrap">
                <label for="max_children_old" class="text-xl flex-1">Max. Kinder (2. Kl. - 6. Kl.)</label>
                <input type="number" min="0" id="max_children_old" name="max_children_old"
                       value="{{ old('max_children_old') }}"
                       class="w-full md:w-auto flex-grow rounded-lg py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10 flex-wrap">
                <label for="max_children_young" class="text-xl flex-1">Max. Kinder (3 Jahre - 1. Kl.)</label>
                <input type="number" min="0" id="max_children_young" name="max_children_young"
                       value="{{ old('max_children_young') }}"
                       class="w-full md:w-auto flex-grow rounded-lg py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10 flex-wrap">
                <label for="max_babies" class="text-xl flex-1">Max. Kinderhüti</label>
                <input type="number" min="0" id="max_babies" name="max_babies" class="w-full md:w-auto flex-grow rounded-lg py-1 px-3"
                       value="{{ old('max_babies') }}"
                       required>
            </div>
            <button
                class="mx-auto block bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-lg text-xl mt-12">
                Erstellen
            </button>
        </form>

    </div>


</x-app-layout>
