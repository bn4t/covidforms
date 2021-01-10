<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event erstellen') }}
            </h2>
        </div>
    </x-slot>

    <div class="mt-10 max-w-3xl mx-auto">

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
            <div class="flex items-center mb-10">
                <label for="title" class="text-xl flex-1">Titel</label>
                <input type="text" id="title" name="title" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10">
                <label for="date" class="text-2xl flex-1">Datum</label>
                <input style="width: 15rem;" type="date" id="date" name="date" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <div class="flex items-baseline mb-10">
                <label for="description" class="text-xl flex-1">Beschreibung</label>
                <textarea id="description" name="description" cols="18" rows="6" class="flex-grow rounded-xl py-1 px-3" required></textarea>
            </div>
            <div class="flex items-center mb-10">
                <label for="max_adults" class="text-xl flex-1">Max. Erwachsene</label>
                <input type="number" min="0" id="max_adults" name="max_adults" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10">
                <label for="max_children_old" class="text-xl flex-1">Max. Kinder (2. Kl. - 6. Kl.)</label>
                <input type="number" min="0" id="max_children_old" name="max_children_old" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10">
                <label for="max_children_young" class="text-xl flex-1">Max. Kinder (3 Jahre - 1. Kl.)</label>
                <input type="number" min="0" id="max_children_young" name="max_children_young" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <div class="flex items-center mb-10">
                <label for="max_babies" class="text-xl flex-1">Max. Kinderhüti</label>
                <input type="number" min="0" id="max_babies" name="max_babies" class="flex-grow rounded-xl py-1 px-3" required>
            </div>
            <button class="mx-auto block bg-gray-800 hover:bg-gray-700 text-white font-semibold py-1 px-3 rounded-xl text-xl mt-12">Erstellen</button>
        </form>

    </div>


</x-app-layout>
