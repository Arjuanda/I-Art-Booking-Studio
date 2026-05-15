<div id="notifDropdown" class="hidden absolute top-full right-8 -mt-4 w-72 bg-white rounded-xl shadow-lg z-50 text-black">
    <div class="rounded-4xl max-h-55 overflow-y-auto scroll-hide">
        @forelse(auth()->user()->notifications()->orderBy('created_at', 'desc')->get() as $notif)
        <div class="p-3 cursor-pointer border-b flex justify-between items-center 
        {{ is_null($notif->read_at) ? 'bg-white text-gray-500' : 'bg-white text-gray-500' }}">
    
            <div>
                <span class="font-medium">{{ $notif->data['message'] }}</span><br>
                <span class="font-small text-sm">{{ \Carbon\Carbon::parse($notif->data['start'])->translatedFormat('d F Y') }}</span><br>
                <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
        
            @if($notif->type == 'booking_status')
            @if(($notif->data['status'] ?? null) == 'approved')
                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">
                    Disetujui
                </span>
            @else
                <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">
                    Ditolak
                </span>
            @endif
            @endif
        </div>
        @empty
            <div class="p-3 text-gray-500 text-center">
                Tidak ada notifikasi
            </div>
        @endforelse
    </div>
</div>
<script>
    const notifBtn = document.getElementById('notifBtn');
    const notifDropdown = document.getElementById('notifDropdown');

    notifBtn.addEventListener('click', () => {
        notifDropdown.classList.toggle('hidden');

        // 🔥 pindahkan ke sini
        fetch('/notifications/read-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            const badge = document.querySelector('.bg-red-500');
            if (badge) badge.remove();
        });
    });

    document.addEventListener('click', function(e) {
        if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
</script>