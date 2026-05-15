<x-layout>
    <div class="flex justify-between items-center mb-4">
        <x-slot:title>{{ $title }}</x-slot:title>
    </div>

<header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 mb-2 bg-white shadow-lg rounded-2xl px-6 py-5 ">
    <div class="hidden">
        <div class="hidden gap-3 sm:flex">
            <button id="tableSwitch" class="text-gray-500 cursor-pointer">Table</button>
            <button id="calendarSwitch" class="cursor-pointer">Calendar</button>
        </div>
    </div>
    <div class="flex items-center ml-auto gap-2">
        <div class="relative shrink-0 max-w-md">
            <a href="javascript:void(0)" onclick="openCreateFromTable()" class="inline-block px-4 py-2 bg-blue-500 rounded-4xl text-white hover:bg-blue-700 gap-2">
                <div class="flex justify-between items-center gap-2">
                    <i class="ti ti-users text-white text-lg leading-none"></i>
                    <p class="flex">Add {{ $title }}</p>
                </div>
            </a>
        </div>
    </div>
    <div id="addModal" class="fixed inset-0 transition-transition bg-black/60 duration-300 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center z-50">
        <div id="modalContent" class="bg-white p-6 rounded-2xl transform {{ $errors->any() ? 'scale-100 opacity-100' : 'scale-90 opacity-0' }} transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <h3 id="modalTitleBooking" class="text-lg font-semibold items-center">Add Schedule</h3>
                <button data-close="modal" data-reset="true" class="items-center px-2 text-black rounded-full hover:bg-gray-200 cursor-pointer">X</button>
            </div> 
            <div class="flex flex-col">
                <form action="" @submit.prevent="
                    if(!$refs.start.value || !$refs.end.value) {
                        alert('Jam mulai & selesai wajib diisi');
                        return;
                    }
                    $el.submit();" id="bookingForm" method="POST" class="my-4" enctype="multipart/form-data" novalidate>
                    @csrf
                    <span id="statusBadge" class="hidden flex-shrink-0 badge-status"></span>
                    @if ($errors->has('time'))
                        <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                            {{ $errors->first('time') }}
                        </div>
                    @endif
                    <div class="flex items-start gap-x-4 mb-2">
                        <div class="flex flex-col flex-1">
                            <input id="userName" type="text" value="{{ auth()->user()->name }}" name="title" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg" readonly>
                        </div>
                        <div class="flex flex-col flex-1">            
                            <input type="date" id="date" name="date" class=" date-picker:cursor-pointer w-16xs my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                            @error('date')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>  
                    </div>
                    <div class="flex items-start gap-x-4 mb-2">
                        <div class="flex flex-col flex-1 relative"
                            x-data="{
                                open:false,
                                value: '{{ old('start') }}',
                                options: generateTimeOptions(7, 21),
                                isDisabled(jam) {
                                    return window.disabledTimes.includes(jam)
                                }
                            }">
                            <input type="hidden" id="start" name="start" x-model="value" x-ref="start">
                            <button type="button"
                                @click="open = !open"
                                class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 rounded-lg text-left"
                                :class="value ? 'text-black' : 'text-gray-400'">
                                <span x-text="value || 'Jam Mulai'"></span>
                            </button>
                            <ul x-show="open"
                                x-transition
                                @click.outside="open = false"
                                class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                <template x-for="jam in options" :key="jam">
                                    <li @click="!isDisabled(jam) && (value = jam, open = false)"
                                        class="px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                        :class="isDisabled(jam)
                                            ? 'text-gray-400 cursor-not-allowed'
                                            : 'hover:bg-blue-100'"
                                        x-text="jam">
                                    </li>
                                </template>
                            </ul>
                            @error('start')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="flex flex-col flex-1 relative"
                            x-data="{
                                open:false,
                                value: '{{ old('end') }}',
                                options: generateTimeOptions(8, 22),
                                isDisabled(jam) {
                                    return window.disabledTimes.includes(jam)
                                },
                                startValue() {
                                    return document.querySelector('input[name=start]')?.value
                                }
                            }">
                            <input type="hidden" id="end" name="end" x-model="value" x-ref="end">
                            <button type="button"
                                @click="open = !open"
                                class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 rounded-lg text-left"
                                :class="value ? 'text-black' : 'text-gray-400'">
                                <span x-text="value || 'Jam Selesai'"></span>
                            </button>
                            <ul x-show="open"
                                x-transition
                                @click.outside="open = false"
                                class="absolute z-50 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-40 overflow-y-auto">
                                <template x-for="jam in options.filter(j => !startValue() || j > startValue())" :key="jam">
                                    <li @click="!isDisabled(jam) && (value = jam, open = false)"
                                        :class="{
                                            'text-gray-400 cursor-not-allowed bg-gray-100': isDisabled(jam),
                                            'hover:bg-blue-100 cursor-pointer': !isDisabled(jam)
                                        }"
                                        class="px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                        x-text="jam">
                                    </li>
                                </template>
                            </ul>
                            @error('end')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> 
                    <input type="file" name="documentation" id="documentationInput" accept="image/*" class=" hidden form-control @error('poster') is-invalid @enderror file:mr-4 mb-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-white file:cursor-pointer  hover:file:bg-gray-700 w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg cursor-pointer"></br>    
                    <img id="documentationPreview" alt="Documentation Preview" class="w-full h-48 object-cover rounded-lg mb-2 hidden">
                    <div class="flex justify-end gap-2" id="userButton">
                        <button type="submit" id="submitButton" class="text-center my-2 bg-green-400 hover:bg-green-500/100 py-2 px-4 rounded-4xl cursor-pointer">Add</button>
                        <button type="button" data-close="modal" data-reset="true" class="text-center my-2 bg-red-100 hover:bg-red-300 text-red-400 py-2 px-4 rounded-4xl cursor-pointer">Discard</button>
                    </div>
                    <div class="hidden justify-end gap-2" id="adminButton">
                        <button type="submit" name="status" value="approved" class="text-center my-2 bg-green-400 hover:bg-green-500/100 py-2 px-4 rounded-4xl cursor-pointer">Approve</button>
                        <button type="submit" name="status" value="rejected" class="text-center my-2 bg-red-100 hover:bg-red-300 text-red-400 py-2 px-4 rounded-4xl cursor-pointer">Reject</button>
                    </div>
                </form>
            </div>           
        </div>
    </div>
</header>

    <x-calendar id="calendarView" class="bg-white rounded-2xl shadow-lg mt-8 hidden"></x-calendar>
    <div id="tableView" class="">
    <div class="mt-6 hidden md:flex md:flex-col shadow-lg md:rounded-2xl">
        <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 overflow-x-auto">
                <div class="overflow-hidden md:rounded-2xl px-6 bg-white">
                    <table class="min-w-full divide-y table-fixed divide-gray-300 bg-transparent ">
                        <thead class="bg-transparent pt-8">
                            <tr> 
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold pt-8 w-40">Name</th>
                            <th scope="col" class="hidden sm:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-36">Date</th>
                            <th scope="col" class="hidden md:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Time</th>
                            <th scope="col" class="hidden py-3.5 lg:table-cell px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Duration</th> 
                            <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Status</th> 
                            <th scope="col" class="py-3.5 px-3 text-right text-sm pl-3 pr-8 font-semibold pt-8 w-10">Action</th>
                            </tr>
                        </thead>
                        @forelse ($data as $item)
                        <tbody class="bg-transparent">
                            <tr>
                                <td class="w-full sm:w-auto max-w-0 sm:max-w-none whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6 truncate">{{ $item->bookMaker->name }}
                                    <dl class="lg:hidden font-normal">
                                        <dt class="sr-only sm:hidden">Date</dt>
                                        <dd class="sm:hidden text-gray-700 mt-1 truncate"></dd>
                                        <dt class="sr-only">Time</dt>
                                        <dd class="md:hidden text-gray-500 mt-1 md:text-gray-700 truncate"></dd>
                                        <dt class="sr-only">Duration</dt>
                                        <dd class="text-gray-500 mt-1 sm:text-gray-500 truncate"></dd>
                                    </dl>
                                </td>
                                <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item['start']->format('Y-m-d') }}</td>
                                <td class="hidden md:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item['start']->format('H:i') }} - {{ $item['end']->format('H:i') }}</td>
                                <td class="hidden lg:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item['start']->locale('id')->diffForHumans($item['end'], true) }}</td>
                                <td class="whitespace-nowrap pl-2">
                                    <span class="badge-status" data-value="{{ $item->display_status }}">{{ $item->display_status }}</span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-1">
                                    <a href="javascript:void(0)" onclick="openEditFromTable({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900">
                                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-3 z-0"></iconify-icon>
                                    </a>
                                    <a href="{{ route('bookings.destroy', $item->id) }}" 
                                    class="text-red-600 hover:text-red-900" 
                                    onclick="deleteItem(event, '{{ route('bookings.destroy', $item->id) }}')">
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
    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-1 md:hidden">
        <div class="shadow-lg relative flex items-center space-x-3 rounded-2xl bg-white px-6 py-5">
            <div class="min-w-0 flex-1">
                <div class="flex flex-row items-center space-x-3">
                    <p class="truncate text-sm font-medium">{{ $item->bookMaker->name }}</p>
                    <span class="inline-block flex-shrink-0 badge-status" data-value="{{ $item->display_status }}">{{ $item->display_status }}</span>
                </div>
                <p class="mt-1 truncate text-sm text-gray-700">{{ $item['start']->format('Y-m-d') }}</p>
                <p class="mt-1 truncate text-sm text-gray-500">{{ $item['start']->format('H:i') }} - {{ $item['end']->format('H:i') }}</p>
                <p class="mt-1 truncate text-sm text-gray-400">{{ $item['start']->locale('id')->diffForHumans($item['end'], true) }}</p>
            </div>
            <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                <div class="flex gap-2">
                    <a href="javascript:void(0)" onclick="openEditFromTable({{ $item->id }})" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-0.5"></iconify-icon>
                        <span class="sr-only">. Edit</span>
                    </a>
                    <a href="{{ route('bookings.destroy', $item->id) }}" class="text-red-600 hover:text-red-900" onclick="deleteItem(event, '{{ route('bookings.destroy', $item->id) }}')">
                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md text-black p-1"></iconify-icon>
                        <span class="sr-only">. Delete</span>        
                    </a>
                </div>
            </form>
        </div>
    </div>
    @empty
    @endforelse
</x-layout>