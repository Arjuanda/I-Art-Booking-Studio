<div>
    <header id="header2" class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 mb-2 bg-white shadow-lg rounded-2xl px-6 py-5 mt-6">
        <div class="relative">
            <input type="text" placeholder="Cari di sini..." class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm sm:text-base">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-800 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <div class="items-center gap-2">
            <div class="relative w-full max-w-md">
                <a href="javascript:void(0)" id="openModal" data-open="modal" data-type="event" data-action="create" class="inline-block px-4 py-2 bg-blue-500 rounded-4xl text-white hover:bg-blue-700 gap-2">
                    <i class="ti ti-users text-white text-lg leading-none"></i>
                    Add {{ $slot }}
                </a>
            </div>
        </div>
    </header>
</div>