<x-layout>
    
    <div class="flex justify-between items-center mb-4">
        <x-slot:title>{{ $title }}</x-slot:title>
    </div>
    
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 mb-2 bg-white shadow-lg rounded-2xl px-6 py-5 ">
        <div class="flex items-center gap-2 w-full">
            <div class="relative sm:hidden">
                <input type="text" placeholder="Cari di sini..." class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm sm:text-base">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-800 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <div class="relative shrink-0 ml-auto">
                <a href="javascript:void(0)" id="openModal" data-open="modal" data-type="event" data-action="create" class="inline-block px-4 py-2 bg-blue-500 rounded-4xl text-white hover:bg-blue-700 gap-2">
                    <div class="flex justify-between items-center gap-2">
                        <i class="ti ti-users text-white text-lg leading-none"></i>
                        <p class="hidden sm:flex">Add {{ $title }}</p>
                    </div>
                </a>
            </div>
        </div>
        <div id="addModal" class="fixed inset-0 transition-transition bg-black/60 duration-300 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center z-50">
            <div id="modalContent" class="bg-white p-6 rounded-2xl transform {{ $errors->any() ? 'scale-100 opacity-100' : 'scale-90 opacity-0' }} transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <h3 id="modalTitleEvent" class="text-lg font-semibold items-center">Add Event</h3>
                    <button data-close="modal" data-reset="true" class="items-center px-2 text-black rounded-full hover:bg-gray-200 cursor-pointer">X</button>
                </div> 
                <div class="flex flex-col">
                    <form action="/events" id="eventForm" method="POST" class="my-4" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="flex items-start gap-x-4 mb-2">
                                <div class="flex flex-col">
                                    <input type="text" placeholder="Title" value="{{ old('title') }}" name="title" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                    @error('title')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="flex flex-col">            
                                    <input type="date" placeholder="Date" value="{{ old('date') }}" name="date" class=" date-picker:cursor-pointer w-16xs my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                    @error('date')
                                        <div class="text-red-500 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>  
                            </div>
                                <textarea name="description" placeholder="Description" id="" class="w-full mb-2 my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">{{ old('description') }}</textarea></br>
                                @error('description')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                                <input type="file" name="poster" id="posterInput" accept="image/*" class="form-control @error('poster') is-invalid @enderror file:mr-4 mb-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-white file:cursor-pointer  hover:file:bg-gray-700 w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg cursor-pointer"> 
                                <small class="block text-gray-500 -mt-4 italic pl-2">Max: 2mb</small></br>
                                @error('poster')
                                    <div id="posterError" class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                                <img id="posterPreview" alt="Poster Preview" class="w-full h-48 object-cover rounded-lg mb-2 hidden">
                                <div class="flex justify-end gap-2">
                                    <button type="submit" id="submitButton" class="text-center my-2 bg-green-400 hover:bg-green-500/100 py-2 px-4 rounded-4xl cursor-pointer">Add</button>
                                    <button type="button" data-close="modal" data-reset="true" class="text-center my-2 bg-red-100 hover:bg-red-300 text-red-400 py-2 px-4 rounded-4xl cursor-pointer">Discard</button>
                                </div>
                    </form>
                </div>           
            </div>
        </div>
        @if ($errors->any())
        <script>
        document.addEventListener("DOMContentLoaded", function () {

            const modal = document.getElementById('addModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            content.classList.remove('scale-90','opacity-0');
            content.classList.add('scale-100','opacity-100');

            const input = document.getElementById('posterInput');
            const preview = document.getElementById('posterPreview');
            const error = document.getElementById('posterError');

            if (input) {
                input.onchange = null;

                input.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file) return;

                    if (!['image/jpeg','image/png'].includes(file.type)) {
                        if (error) error.textContent = 'File harus berupa gambar (JPG / PNG)';
                        this.value = '';
                        preview.classList.add('hidden');
                        return;
                    }

                    if (error) error.textContent = '';

                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                });
            }

        });
        </script>
        @endif
    </header>

    <div class="mt-6 hidden md:flex md:flex-col shadow-lg md:rounded-2xl">
        <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 overflow-x-auto">
                <div class="overflow-hidden md:rounded-2xl px-6 bg-white">
                    <table class="min-w-full divide-y table-fixed divide-gray-300 bg-transparent ">
                        <thead class="bg-transparent pt-8">
                            <tr> 
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold pt-8 w-40">Created By</th>
                               <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold pt-8 w-40">Title</th>
                               <th scope="col" class="hidden md:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-60 truncate">Description</th>
                               <th scope="col" class="hidden lg:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-36">Date</th>
                               <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Poster</th> 
                               <th scope="col" class="py-3.5 px-3 text-right text-sm pl-3 pr-8 font-semibold pt-8 w-10">Action</th>
                            </tr>
                        </thead>
                        @forelse ($data as $item)
                        <tbody class="bg-transparent">
                            <tr>
                                <td class="w-full sm:w-auto max-w-0 sm:max-w-none whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6 truncate">{{ $item->eventMaker->name }}
                                <td class="w-full sm:w-auto max-w-0 sm:max-w-none whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6 truncate">{{ $item ['title'] }}
                                    <dl class="lg:hidden font-normal">
                                        <dt class="sr-only md:hidden">Description</dt>
                                        <dd class="md:hidden text-gray-700 mt-1 truncate">{{ $item ['description'] }}</dd>
                                        <dt class="sr-only">Date</dt>
                                        <dd class="text-gray-500 mt-1 sm:text-gray-700 truncate">{{ $item ['date'] }}</dd>
                                    </dl>
                                    </td>
                                <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{Str::limit( $item ['description'],50) }}</td>
                                <td class="hidden lg:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item ['date'] }}</td>
                                <td class="whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{Str::limit( $item ['poster'],50) }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-1">
                                    <a href="javascript:void(0)" data-open="modal" data-type="event" data-action="edit" data-id="{{ $item->id }}" class="text-indigo-600 hover:text-indigo-900">
                                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-3 z-0"></iconify-icon>
                                    </a>
                                    <a href="{{ route('events.destroy', $item->id) }}" 
                                    class="text-red-600 hover:text-red-900" 
                                    onclick="deleteItem(event, '{{ route('events.destroy', $item->id) }}')">
                                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md text-black p-1"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                                @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Card View md breakpoint -->
    @forelse ($data as $item)
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-1 md:hidden">
        <div class="shadow-lg relative flex items-center space-x-3 rounded-2xl bg-white px-6 py-5">
            <div class="min-w-0 flex-1">
                <div class="flex items-center space-x-3">
                    <p class="truncate text-sm font-medium">{{ $item->eventMaker->name }}</p>
                </div>
                <p class="mt-1 truncate text-sm text-gray-900">{{ $item ['title'] }}</p>
                <p class="mt-1 truncate text-sm text-gray-700">{{ $item ['description'] }}</p>
                <p class="mt-1 truncate text-sm text-gray-500">{{ $item ['date'] }}</p>
            </div>
            <form action="{{ route('events.destroy', $item->id) }}" method="POST">
                <div class="flex gap-2">
                    <a href="javascript:void(0)" data-open="modal" data-type="event" data-action="edit" data-id="{{ $item->id }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-0.5"></iconify-icon>
                        <span class="sr-only">. Edit</span>
                    </a>
                    <a href="{{ route('events.destroy', $item->id) }}" class="text-red-600 hover:text-red-900" onclick="deleteItem(event, '{{ route('events.destroy', $item->id) }}')">
                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md text-black p-1"></iconify-icon>
                        <span class="sr-only">. Delete</span>        
                    </a>
                </div>
            </form>
        </div>
    </div>
    @empty
    @endforelse
@if (session('modal'))
<script>
document.addEventListener('DOMContentLoaded', async function () {
    const modal = @json(session('modal'));

    if (modal.type === 'event') {
        const form = document.querySelector('#eventForm');
        const submitButton = document.getElementById('submitButton');
        const modalTitleEvent = document.getElementById('modalTitleEvent');
        const posterPreview = document.getElementById('posterPreview');

        openModal();

        initImageInput({
            inputId: 'posterInput',
            previewId: 'posterPreview',
            errorId: 'posterError'
        });

        if (modal.action === 'edit') {
            form.action = `/events/${modal.id}`;

            let methodInput = form.querySelector('[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            modalTitleEvent.innerText = 'Edit Event';
            submitButton.innerText = 'Update';

            const res = await fetch(`/events/${modal.id}/edit`);
            const data = await res.json();

            const eventform = document.querySelector('#eventForm');
            fillForm(data, eventform);


            if (data.poster) {
                posterPreview.src = `/storage/images/${data.poster}`;
                posterPreview.classList.remove('hidden');
            }
        }
    }
});
</script>
@endif
</x-layout>