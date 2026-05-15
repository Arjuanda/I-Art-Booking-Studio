<x-layout2>
    <div class="bg-black min-h-screen pb-5">
        <div class="pt-30 pb-2 rounded-4xl overflow-hidden">
            <div class="px-6 md:px-20 pb-5 relative">
                <x-bg class="relative h-60 w-full rounded-2xl ring-2 ring-gray-200 overflow-hidden"></x-bg>
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="flex flex-col absolute inset-0 items-center justify-center text-white z-10">
                    <h1 class=" text-4xl md:text-6xl font-semibold">
                        GALLERY I-ART
                    </h1>
                    <p class="text-xl md:text-2xl pt-2">Mari Bermusik</p>
                </div>
            </div>
        </div>
        @if($user->status === 'active' || $user->status === 'pending')
        <div class="px-7 md:px-21">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse ($data as $item)
                    <div class="w-full">
                        <x-post :item="$item"  class="bg-gray-900 rounded-2xl shadow-lg -mb-2 mt-4 ring-2 ring-gray-200 {{ $iconColor ?? 'text-gray-200' }}  overflow-hidden"></x-post>
                    </div>
                @empty
                @endforelse
                <div x-data="{
                        open: false,
                        index: 0,
                        pics: [],
                        prev() { this.index = (this.index === 0 ? this.pics.length - 1 : this.index - 1); },
                        next() { this.index = (this.index === this.pics.length - 1 ? 0 : this.index + 1); }
                    }"
                    x-on:open-lightbox.window="open = true; index = $event.detail.index; pics = $event.detail.pics"
                    x-show="open"
                    x-transition
                    class="fixed inset-0 bg-black/80 bg-opacity-70 flex items-center justify-center z-50">

                    <img :src="@js(asset('storage') . '/') + pics[index]" class="max-h-full max-w-full rounded-lg">
                    <button x-on:click="open = false" class="absolute top-4 right-4 text-white text-2xl cursor-pointer">&times;</button>
                    <button x-on:click="prev()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl cursor-pointer">&larr;</button>
                    <button x-on:click="next()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl cursor-pointer">&rarr;</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layout2>