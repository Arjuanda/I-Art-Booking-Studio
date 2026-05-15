<x-layout>
    <div class="flex justify-between items-center mb-4">
        <x-slot:title>{{ $title }}</x-slot:title>
    </div>

<header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 mb-2 bg-white shadow-lg rounded-2xl px-6 py-5 ">
    <div class="hidden gap-3">
        <button id="tableSwitch" class="text-gray-500 cursor-pointer">Table</button>
        <button id="postSwitch" class="cursor-pointer">Posts</button>
    </div>
    <div class="flex items-center ml-auto gap-2">
        <div class="relative shrink-0 max-w-md">
            <a href="javascript:void(0)" id="openModal" data-open="modal" data-type="documentation" data-action="create" class="inline-block px-4 py-2 bg-blue-500 rounded-4xl text-white hover:bg-blue-700 gap-2">
                <div class="flex justify-between items-center gap-2">
                    <i class="ti ti-users text-white text-lg leading-none"></i>
                    <p class="hidden sm:flex">Add Post</p>
                </div>
            </a>
        </div>
    </div>
    <div id="addModal" class="fixed inset-0 transition-transition bg-black/60 duration-300 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center z-50">
        <div id="modalContent" class="bg-white p-6 rounded-2xl transform {{ $errors->any() ? 'scale-100 opacity-100' : 'scale-90 opacity-0' }} transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <h3 id="modalTitleDocumentation" class="text-lg font-semibold items-center">Add Post</h3>
                <button data-close="modal" data-reset="true" class="items-center px-2 text-black rounded-full hover:bg-gray-200 cursor-pointer">X</button>
            </div> 
            @if ($errors->any())
                <div class="text-red-600 text-sm mb-2">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
                <div class="flex flex-col">
                    <form action="/documentations" id="documentationForm" method="POST" class="my-4" enctype="multipart/form-data" novalidate>
                            @csrf
                                <div class="flex flex-col flex-1">
                                    <input type="text" value="{{ auth()->user()->name }}" name="title" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg" readonly>
                                </div>                            
                                <textarea name="caption" placeholder="Caption" id="" class="w-full mb-2 my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">{{ old('caption') }}</textarea></br>
                                @error('caption')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                                <input type="file" id="fileInput" name="pictures[]" multiple accept="image/*" class="form-control @error('pictures') is-invalid @enderror file:mr-4 mb-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-white file:cursor-pointer  hover:file:bg-gray-700 w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg cursor-pointer"></br>    
                                @error('pictures.*')
                                    <div id="picturesError" class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror

                                @php
                                    $pictures = $item->pictures ?? [];
                                @endphp

                                <div id="picturesContainer" class="grid max-h-48 overflow-y-auto {{ count($pictures) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2">
                                    @foreach ($pictures as $index => $pic)
                                        @if($pic)
                                            <div class="relative" data-existing="true" data-index="{{ $index }}">
                                                <img src="{{ asset('storage/' . $pic) }}" alt="Picture Preview"
                                                    class="max-w-full object-cover sm:grid-cols-4 gap-2">
                                                <button type="button" class="delete-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="submit" id="submitButton" class="text-center my-2 bg-green-400 hover:bg-green-500/100 py-2 px-4 rounded-4xl cursor-pointer">Add</button>
                                    <button type="button" data-close="modal" data-reset="true" class="text-center my-2 bg-red-100 hover:bg-red-300 text-red-400 py-2 px-4 rounded-4xl cursor-pointer">Discard</button>
                                </div>
                    </form>
                </div>           
        </div>
        @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                openModal('documentation', 'create');
            });
        </script>
        @endif
    </div>
</header>

    <div id="postView" class="hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @forelse ($data as $item)
            <div class="w-full">
                <x-post :item="$item"  class="bg-white rounded-2xl shadow-lg -mb-2 mt-4  overflow-hidden"></x-post>
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

    <div id="tableView" class="">
    <div class="mt-6 hidden md:flex md:flex-col shadow-lg md:rounded-2xl">
        <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 overflow-x-auto">
                <div class="overflow-hidden md:rounded-2xl px-6 bg-white">
                    <table class="min-w-full divide-y table-fixed divide-gray-300 bg-transparent ">
                        <thead class="bg-transparent pt-8">
                            <tr> 
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold pt-8 w-40">Name</th>
                            <th scope="col" class="hidden sm:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-36">Caption</th>
                            <th scope="col" class="hidden md:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Pictures</th>
                            <th scope="col" class="py-3.5 px-3 text-right text-sm pl-3 pr-8 font-semibold pt-8 w-10">Action</th>
                            </tr>
                        </thead>
                        @forelse ($data as $item)
                        <tbody class="bg-transparent">
                            <tr>
                                <td class="w-full sm:w-auto max-w-0 sm:max-w-none whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6 truncate">{{ $item->postMaker->name }}
                                    <dl class="lg:hidden font-normal">
                                        <dt class="sr-only sm:hidden">Caption</dt>
                                        <dd class="sm:hidden text-gray-700 mt-1 truncate"></dd>
                                        <dt class="sr-only">Pictures</dt>
                                        <dd class="md:hidden text-gray-500 mt-1 md:text-gray-700 truncate"></dd>
                                        <dd class="text-gray-500 mt-1 sm:text-gray-500 truncate"></dd>
                                    </dl>
                                </td>
                                <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item->caption }}</td>
                                <td class="hidden md:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item->pictures ? count($item->pictures) : 0 }} pictures</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-1">
                                    <a href="javascript:void(0)" data-open="modal" data-type="documentation" data-action="edit" data-id="{{ $item->id }}" class="text-indigo-600 hover:text-indigo-900">
                                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-3 z-0"></iconify-icon>
                                    </a>
                                    <a href="{{ route('users.destroy', $item->id) }}" 
                                    class="text-red-600 hover:text-red-900" 
                                    onclick="deleteItem(event, '{{ route('documentations.destroy', $item->id) }}')">
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
    </div>
    @forelse ($data as $item)
    <div class="cardPost mt-6 grid grid-cols-1 gap-4 md:grid-cols-1 md:hidden">
        <div class="shadow-lg relative flex items-center space-x-3 rounded-2xl bg-white px-6 py-5">
            <div class="min-w-0 flex-1">
                <div class="flex flex-row items-center space-x-3">
                    <p class="truncate text-sm font-medium">{{ $item->postMaker->name }}</p>
                    <span class="inline-block flex-shrink-0 badge-role" data-value="{{ $item->postMaker->role }}">{{ $item->postMaker->role }}</span>
                </div>
                <p class="mt-1 truncate text-sm text-gray-700">{{ $item->caption }}</p>
                <p class="mt-1 truncate text-sm text-gray-500">{{ $item->pictures ? count($item->pictures) : 0 }} pictures</p>
            </div>
            <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                <div class="flex gap-2">
                    <a href="javascript:void(0)" data-open="modal" data-type="documentation" data-action="edit" data-id="{{ $item->id }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-0.5"></iconify-icon>
                        <span class="sr-only">. Edit</span>
                    </a>
                    <a href="{{ route('documentations.destroy', $item->id) }}" class="text-red-600 hover:text-red-900" onclick="deleteItem(event, '{{ route('documentations.destroy', $item->id) }}')">
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
    let newFiles = [];
    let removedExisting = [];
    const modal = @json(session('modal'));

    const btn = document.querySelector(
        `[data-open="modal"][data-type="${modal.type}"][data-action="${modal.action}"][data-id="${modal.id}"]`
    );

    if (btn) {
        btn.click();
    }

    if (modal.type === 'documentation' && modal.action === 'edit') {
        const form = document.querySelector('#documentationForm');
        const submitButton = document.getElementById('submitButton');
        const modalTitleDocumentation = document.getElementById('modalTitleDocumentation');
        const picturesContainer = document.getElementById('picturesContainer');
        const fileInput = document.getElementById('fileInput');

        form.action = `/documentations/${modal.id}`;

        let methodInput = form.querySelector('[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';

        modalTitleDocumentation.innerText = 'Edit Content';
        submitButton.innerText = 'Update';

        const res = await fetch(`/documentations/${modal.id}/edit`);
        const data = await res.json();

        fillForm(data, form);

        picturesContainer.innerHTML = '';
        fileInput.value = '';

        if (data.pictures && data.pictures.length > 0) {

            data.pictures.forEach((pic, index) => {

                const wrapper = document.createElement('div');
                wrapper.classList.add('relative');
                wrapper.dataset.existing = true;
                wrapper.dataset.index = index;

                const img = document.createElement('img');
                img.src = `/storage/${pic}`;
                img.classList.add('w-50', 'h-47','object-cover','rounded-lg');

                const btn = document.createElement('button');
                btn.innerHTML = '&times;';
                btn.classList.add(
                    'delete-btn',
                    'absolute','top-0','right-0',
                    'text-red-700',
                    'w-8','h-8',
                    'flex','items-center','justify-center'
                );

                wrapper.appendChild(img);
                wrapper.appendChild(btn);

                picturesContainer.appendChild(wrapper);
            });

        }
        fileInput.onchange = (e) => {

            const files = Array.from(e.target.files);
            files.forEach(file => {
                newFiles.push(file);
                const reader = new FileReader();
                reader.onload = (event) => {
                    addPicturePreview(event.target.result, file);
                };
                reader.readAsDataURL(file);
            });
            fileInput.value = '';
        };

        picturesContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-btn')) {
                const wrapper = e.target.parentElement;
                if (wrapper.dataset.existing) {
                    removedExisting.push(wrapper.dataset.index);
                }
                wrapper.remove();
            }
        });
        form.onsubmit = () => {

            const dataTransfer = new DataTransfer();

            newFiles.forEach(file => {
                dataTransfer.items.add(file);
            });

            fileInput.files = dataTransfer.files;

            const old = form.querySelector('input[name="removedExisting"]');
            if (old) old.remove();

            let removedInput = document.createElement('input');
            removedInput.type = 'hidden';
            removedInput.name = 'removedExisting';
            removedInput.value = JSON.stringify(removedExisting);

            form.appendChild(removedInput);
        };
    }
});
</script>
@endif
</x-layout>