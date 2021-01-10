<label for="{{ $name }}" class="block mb-1 flex justify-between items-center px-1">
    <span>{{ $title }}</span>
    <span class="bg-blue-500 text-white rounded-3xl px-2 text-sm">{{ $remaining_seats }} Plätze Frei</span>
</label>
<input type="number" id="{{$name}}" name="{{$name}}" class="py-1 px-3 rounded-lg bg-white w-full"
       max="{{ $remaining_seats }}" min="0" required @if($remaining_seats == 0) disabled value="0" @endif>
