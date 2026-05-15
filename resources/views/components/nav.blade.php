<nav id="navbar" class="flex fixed w-full top-0 z-50 items-center justify-between pr-10 md:pr-20 h-18 transition-all duration-300 bg-gray-900">  
    <div class="flex items-center gap-6 h-18">    
        <div class="hidden sm:flex gap-2 shadow-lg bg-gray-500 h-full items-center px-20 text-black relative justify-start"
            style="clip-path: polygon(0% 0, 80% 0, 100% 100%, 0% 100%);">
            <img class="h-11 w-11" src="/storage/images/logo.png" alt="Logo">
            <div class="hidden lg:flex text-2xl font-semibold items-center signature text-gray-900">I-Art</div>
        </div>

        <ul class="text-sm text-gray-300">
            <li><a href="/landing-page" class="hover:text-gray-100 pl-10 sm:pl-0">Home</a></li>
        </ul>
    </div>
        <div class="flex gap-4 items-center justify-end text-slate-600">
            <div class="relative order-2 lg:order-1">
                <iconify-icon id="notifBtn" icon="clarity:notification-line" class="text-xl p-2 mt-1 text-white hover-gradient rounded-full cursor-pointer"></iconify-icon>
                @if(($notifications ?? collect())->whereNull('read_at')->count())
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full">
                        {{ ($notifications ?? collect())->whereNull('read_at')->count() }}
                    </span>
                @endif
            </div>
            <x-notif class="order-3 lg:order-3"></x-notif>
            @if (auth()->check())
            <a href="/profile" class="p-2 rounded-2xl hover:bg-gray-700">
                @if (auth()->check())
                <h6 class="text-bold text-white order-1 lg:order-2">Hi {{ explode(' ', auth()->user()->name)[0] }}!</h6>
                @endif
            </a>
            @endif
            <div class="rounded-full bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 cursor-pointer order-4">
                <a href="/logout">Logout</a>
            </div>
        </div>
</nav>
<script>
    document.getElementById('notifBtn').addEventListener('click', function () {

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);

            // hapus badge kalau sukses
            const badge = document.querySelector('.notif-badge');
            if (badge) badge.remove();
        });

    });
</script>