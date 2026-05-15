@props(['item'])
<div {{ $attributes->merge(['class' => 'gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 shadow-lg rounded-2xl px-4 sm:px-6 py-5']) }}>
    <div class="flex justify-between gap-2 flex-col">
        <div class="relative shrink-0 max-w-md md:max-w-full flex items-center gap-3">
            <div class="flex justify-between flex-1">
                <div class="flex flex-col">
                    <h6 class="font-bold text-dark mt-1">{{ data_get($item, 'postMaker.name', 'User') }}</h6>
                    <p class="text-xs text-gray-500">{{ data_get($item, 'created_at') ? \Carbon\Carbon::parse(data_get($item, 'created_at'))->diffForHumans() : '-' }}</p>
                </div>
                @if(Auth::id() == data_get($item, 'user_id') || data_get(Auth::user(), 'role') == 'admin')
                <div class="relative">
                    <button onclick="toggleMenu(this)" class="cursor-pointer">
                        <iconify-icon icon="bi:three-dots-vertical" class="text-md text-black p-1"></iconify-icon>
                    </button>

                    <div class="dropdown hidden absolute right-0 bg-white shadow rounded px-4 py-2 z-40">
                        <a href="javascript:void(0)" data-open="modal" data-type="documentation" data-action="edit" data-id="{{ $item->id }}" class="text-indigo-600 hover:text-indigo-900">
                            <button type="submit" class="items-center px-2 text-black rounded-full cursor-pointer">Edit</button>
                                    </a>
                        <a href="{{ route('users.destroy', $item->id) }}" 
                                    class="text-red-600 hover:text-red-900" 
                                    onclick="deleteItem(event, '{{ route('documentations.destroy', $item->id) }}')">
                            <button type="submit" class="items-center px-2 text-red-700 rounded-full cursor-pointer">Delete</button>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-3 pl-3">
        <div>
            <p>{{ data_get($item, 'caption', '') }}</p>
            @php
                $pictures = collect(data_get($item, 'pictures', []))->toArray();
            @endphp

            <div class="grid {{ count($pictures) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2">
                @foreach($pictures as $i => $pic)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $pic) }}"
                            class="rounded-lg object-contain w-full h-40 cursor-pointer"
                            alt="Picture {{ $i + 1 }}"
                            x-data
                            x-on:click="$dispatch('open-lightbox', { index: {{ $i }}, pics: @js($pictures) })">

                        @if($i === 1 && count($pictures) > 2)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-lg font-bold rounded-lg cursor-pointer"
                                x-data
                                x-on:click="$dispatch('open-lightbox', { index: 0, pics: @js($pictures) })">
                                +{{ count($pictures) - 2 }}
                            </div>
                        @endif
                    </div>

                    @if($i >= 1 && count($pictures) > 2) @break @endif
                @endforeach
            </div>
        </div>
        </form>
    </div> 
</div>
<script>
function toggleMenu(button) {
    const menu = button.parentElement.querySelector(".dropdown");
    menu.classList.toggle("hidden");
}

document.addEventListener("click", function(e) {
    document.querySelectorAll(".dropdown").forEach(menu => {
        if (!menu.parentElement.contains(e.target)) {
            menu.classList.add("hidden");
        }
    });
});
</script>
