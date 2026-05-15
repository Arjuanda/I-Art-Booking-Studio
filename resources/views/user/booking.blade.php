<x-layout2>
    <div class="bg-black min-h-screen pb-5">
        <div class="pt-25 sm:pt-30 pb-2 rounded-4xl overflow-hidden">
            <div class="px-6 md:px-20 pb-5 relative">
                <x-bg class="relative h-60 w-full rounded-2xl ring-2 ring-gray-200 overflow-hidden"></x-bg>
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="flex flex-col absolute inset-0 items-center justify-center text-white z-10">
                    @if($user->status === 'active')
                    <h1 class=" text-4xl md:text-6xl font-semibold">
                        BOOKING STUDIO
                    </h1>
                    <p class="text-xl md:text-2xl pt-2">Buat Jadwal Bermusikmu</p>
                    @elseif($user->status === 'pending')
                    <h1 class=" text-4xl md:text-6xl font-semibold">
                        AKUN ANDA BELUM AKTIF
                    </h1>    
                    @else
                    <h1 class=" text-4xl md:text-6xl font-semibold">
                        AKUN ANDA DI BAN
                    </h1>    
                    @endif               
                </div>
            </div>
        </div>
        @if($user->status === 'active')
        <div class="px-6 md:px-20">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-6 lg:items-start">
                <div class="flex-1 bg-gray-900 rounded-2xl shadow p-4 min-h-[155px]  max-h-[383px] scroll-hide ring-2 ring-gray-200">
                    <h2 class="text-2xl text-gray-200 font-bold mb-4">JADWAL SAYA</h2>
                    <div class="overflow-y-auto max-h-80 scroll-hide">
                        <table class="min-w-full text-sm text-left">
                            <tbody>
                                @forelse ($data as $item)
                                <tr class="border-b transition">
                                    
                                    <td class="py-3 px-4 text-gray-200">
                                        {{ $item['start']->format('Y-m-d') }}
                                    </td>

                                    <td class="py-3 px-4 text-gray-200">
                                        {{ $item['start']->format('H:i') }} - 
                                        {{ $item['end']->format('H:i') }}
                                    </td>

                                    <td class="py-3 px-4 text-gray-200">
                                        {{ $item['start']->locale('id')->diffForHumans($item['end'], true) }}
                                    </td>

                                    <td class="py-3 px-4">
                                    <span class="badge-status" data-value="{{ $item->display_status }}">{{ $item->display_status }}</span>
                                    </td>

                                    <td class="py-3 px-4 text-right space-x-2">
                                        @if($item->status === 'approved')
                                            <a href="{{ route('ticket.show', $item->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                                <iconify-icon icon="boxicons:ticket-filled" class="text-md text-blue-500 p-1 mt-3 z-0"></iconify-icon>
                                            </a>
                                        @endif
                                        @if($item->status === 'pending')
                                            <a href="{{ route('booking.destroy', $item->id) }}" 
                                                class="text-red-600 hover:text-red-900 pr-1" 
                                                onclick="deleteItem(event, '{{ route('booking.destroy', $item->id) }}')">
                                                <iconify-icon icon="solar:trash-bin-minimalistic-outline"></iconify-icon>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-sm py-6 text-gray-400">
                                        Tidak ada data
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex-1 flex flex-col  bg-gray-900 rounded-2xl shadow px-4 pt-4 py-7 ring-2 ring-gray-200 min-h-[155px]">
                    <div class="flex justify-between pb-5 z-10">
                        <h2 class="text-2xl font-bold mb-4 text-gray-200">BUAT JADWAL</h2>
                        <input type="date" id="datePicker" name="date" class="bg-tosca-dark text-accent-cream px-4 py-2 rounded-2xl text-gray-200 border border-tosca-medium focus:outline-none focus:ring-1 focus:ring-tosca-light focus:border-tosca-light shadow-sm w-2/5 cursor-pointer"/>
                    </div>

                    <p id="pickDateMessage" class="text-gray-400 text-sm pt-3 text-center">Pilih Tanggal Terlebih Dahulu</p>

                    <div id="timeGridWrapper" class="hidden">
                        <x-time-grid :slots="$slots" :bookedSlots="$bookedSlots" />
                        <button type="submit" id="submitBooking" class="w-full text-center my-2 bg-[#6dadbe] hover:bg-[#2d7285] py-2 px-4 rounded-2xl cursor-pointer mt-6">Jadwalkan</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <script>
        function disablePastSlots() {
        const selectedDate = document.getElementById('datePicker').value;
        if (!selectedDate) return;

        const now = new Date();

        const [year, month, day] = selectedDate.split('-').map(Number);
        const selected = new Date(year, month - 1, day);

        const today = new Date();
        const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());

        document.querySelectorAll('.slot-btn').forEach(btn => {
            const start = btn.dataset.start;
            const [hour, minute] = start.split(':').map(Number);

            const slotTime = new Date(year, month - 1, day, hour, minute);

            if (selected < todayOnly) {
                btn.classList.add('bg-gray-700', 'opacity-50', 'cursor-not-allowed');
                btn.disabled = true;
                return;
            }

            if (selected.getTime() === todayOnly.getTime() && slotTime < now) {
                btn.classList.add('bg-gray-700', 'opacity-50', 'cursor-not-allowed');
                btn.disabled = true;
            }
        });
    }
    function resetAllSlots() {
        document.querySelectorAll('.slot-btn').forEach(btn => {
            btn.classList.remove(
                'bg-gray-700',
                'opacity-50',
                'cursor-not-allowed',
                'bg-red-500'
            );

            btn.classList.add('hover:bg-blue-500');
            btn.disabled = false;
        });
    }
    let currentRequestDate = null;

    function loadBookings(date) {
        currentRequestDate = date;

        fetch(`/bookings/by-date?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (currentRequestDate !== date) return;

                const bookedMap = new Set();

                data.forEach(booking => {
                    if (booking.status === 'rejected') return;

                    const start = new Date(booking.start);
                    const end   = new Date(booking.end);

                    document.querySelectorAll('.slot-btn').forEach(btn => {
                        const slotStart = new Date(`${date}T${btn.dataset.start}`);
                        const slotEnd   = new Date(`${date}T${btn.dataset.end}`);

                        if (slotStart < end && slotEnd > start) {
                            bookedMap.add(btn);
                        }
                    });
                });

                document.querySelectorAll('.slot-btn').forEach(btn => {
                    const isBooked = bookedMap.has(btn);

                    if (isBooked && !btn.classList.contains('bg-red-500')) {
                        btn.classList.add('bg-red-500', 'cursor-not-allowed','hover:bg-red-900');
                        btn.disabled = true;
                    }

                    // OPTIONAL: kalau mau handle un-book (jarang terjadi)
                    if (!isBooked && btn.classList.contains('bg-red-500')) {
                        btn.classList.remove('bg-red-500','hover:bg-red-900');

                        if (!btn.classList.contains('bg-gray-700')) {
                            btn.disabled = false;
                        }
                    }
                });
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        const datePicker = document.getElementById('datePicker');
        const timeGridWrapper = document.getElementById('timeGridWrapper');
        const pickDateMessage = document.getElementById('pickDateMessage');

        datePicker.addEventListener('change', function() {
            if (this.value) {
                timeGridWrapper.classList.remove('hidden');
                pickDateMessage.classList.add('hidden');
                const selectedDate = this.value;

                resetAllSlots();          
                disablePastSlots();        
                loadBookings(selectedDate);
            } else {
                timeGridWrapper.classList.add('hidden');
                pickDateMessage.classList.remove('hidden');
            }
        });
        setInterval(() => {
            disablePastSlots();
            const selectedDate = document.getElementById('datePicker').value;
            if (selectedDate) {
                loadBookings(selectedDate);
            }
        }, 2000);
    });
    document.getElementById('submitBooking').addEventListener('click', () => {
        const activeSlots = document.querySelectorAll('.slot-btn.ring-2');
        const date = document.getElementById('datePicker').value;
        const slotsData = Array.from(activeSlots).map(btn => ({
            start: btn.dataset.start,
            end: btn.dataset.end
        }));

        fetch('{{ route("booking.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                date: date,
                slots: slotsData 
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    });
    </script>
</x-layout2>