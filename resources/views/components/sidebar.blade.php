    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="bg-white p-5 w-72 hidden lg:flex flex-col gap-5 sticky top-6 self-start z-30 rounded-2xl shadow-lg"
           style="height:calc(100vh - 48px);">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 flex items-center justify-center text-white font-bold">
            <img src="/storage/images/logo.png" alt="">
          </div>
          <div class="logo-text">
            <div class="text-sm font-semibold">I-Art Studio</div>
            <div class="text-xs text-slate-500">Admin System</div>
          </div>
        </div>
      </div>
      <nav class="flex-1 overflow-y-auto scroll-hide mt-0">
        <ul class="space-y-2 sidebar-item">
          <li>
            <x-side-link  href="/dashboard" :active="request()->is('dashboard')">
              <x-slot:icon>
                <iconify-icon icon="solar:screencast-2-line-duotone" class="text-xl p-2" style="color: currentColor"></iconify-icon>
              </x-slot:icon>
              Dashboard</x-side-link>
          </li>
          <li>
            <x-side-link href="/users" :active="request()->is('users')">
              <x-slot:icon>
                <iconify-icon icon="solar:user-circle-line-duotone" class="text-xl p-2ck" style="color: currentColor"></iconify-icon>
              </x-slot:icon>
              User Management</x-side-link>
          </li>
          <li>
            <x-side-link href="/bookings" :active="request()->is('bookings')">
              <x-slot:icon>
                <iconify-icon icon="solar:notification-unread-lines-line-duotone" class="text-xl p-2" style="color: currentColor"></iconify-icon>
              </x-slot:icon>
              Booking</x-side-link>
          </li>
          <li>
            <x-side-link href="/events" :active="request()->is('events')">
              <x-slot:icon>
                <iconify-icon icon="solar:document-text-line-duotone" class="text-xl p-2" style="color: currentColor"></iconify-icon>
              </x-slot:icon>
              Event</x-side-link>
          </li>
          <li>
            <x-side-link href="/documentations" :active="request()->is('documentations')">
              <x-slot:icon>
                <iconify-icon icon="solar:file-text-line-duotone" class="text-xl p-2" style="color: currentColor"></iconify-icon>
              </x-slot:icon>
              Documentation</x-side-link>
          </li>
        </ul>
      </nav>
      <div class=" pt-4 relative hide-menu">
        <div class="bg-blue-50 relative p-6 rounded-lg">
          <div class="flex items-center">
            <div class="ml-4 rtl:mr-4 rtl:ml-0">
              <h5 class="text-lg font-semibold text-dark">{{ explode(' ', auth()->user()->name)[0] }}</h5>
              <p class="text-sm font-normal text-link">{{ auth()->user()->role }}</p>
            </div>
            <div class="ms-auto hs-tooltip hs-tooltip-toggle">
              <a href="/logout" class="text-link hover:text-primary hover:text-blue-400"><iconify-icon icon="solar:logout-line-duotone" class="text-3xl"></iconify-icon> 
                <span class="tooltip hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden" role="tooltip" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(214.8px, -86.4px);" data-popper-placement="top">
                Logout
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </aside>
    <aside id="sidebarMobile" class="p-5 w-72 lg:hidden fixed left-0 top-0 h-full transition-transform flex flex-col justify-between duration-300 -translate-x-full z-40 bg-white shadow-lg">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-300 to-pink-300 flex items-center justify-center text-white font-bold shadow">IA</div>
          <div>
            <div class="text-sm font-semibold">Soft UI Dashboard</div>
            <div class="text-xs text-slate-500">I-Art Studio</div>
          </div>
        </div>
        <button id="btn-close-mobile" class=" px-2 rounded-full hover-gradient cursor-pointer">✕</button>
      </div>

      <nav class="flex-1 mt-4 overflow-y-auto scroll-hide">
        <ul class="space-y-2">
          <li>
            <x-side-link href="/dashboard" :active="request()->is('dashboard')">
              <x-slot:icon>
                <iconify-icon icon="solar:screencast-2-line-duotone" class="text-xl p-2 text-black"></iconify-icon>
              </x-slot:icon>
              Dashboard</x-side-link>
          </li>
          <li>
            <x-side-link href="/users" :active="request()->is('users')">
              <x-slot:icon>
                <iconify-icon icon="solar:user-circle-line-duotone" class="text-xl p-2 text-black"></iconify-icon>
              </x-slot:icon>
              User Management</x-side-link>
          </li>
          <li>
            <x-side-link href="/bookings" :active="request()->is('bookings')">
              <x-slot:icon>
                <iconify-icon icon="solar:notification-unread-lines-line-duotone" class="text-xl p-2 text-black"></iconify-icon>
              </x-slot:icon>
              Booking</x-side-link>
          </li>
          <li>
            <x-side-link href="/events" :active="request()->is('events*',)">
              <x-slot:icon>
                <iconify-icon icon="solar:document-text-line-duotone" class="text-xl p-2 text-black"></iconify-icon>
              </x-slot:icon>
              Event</x-side-link>
          </li>
          <li>
            <x-side-link href="/documentations" :active="request()->is('documentations')">
              <x-slot:icon>
                <iconify-icon icon="solar:file-text-line-duotone" class="text-xl p-2 text-black"></iconify-icon>
              </x-slot:icon>
              Documentation</x-side-link>
          </li>
        </ul>
      </nav>
      <div class="pt-4">
        <div class="bg-blue-50 relative p-6 rounded-lg">
          <div class="flex items-center">
            <div class="ml-4 rtl:mr-4 rtl:ml-0">
              <h5 class="text-lg font-semibold text-dark">{{ explode(' ', auth()->user()->name)[0] }}</h5>
              <p class="text-sm font-normal text-link">{{ auth()->user()->role }}</p>
            </div>
            <div class="ms-auto hs-tooltip hs-tooltip-toggle">
              <a href="/logout" class="text-link hover:text-primary hover:text-blue-400"><iconify-icon icon="solar:logout-line-duotone" class="text-3xl"></iconify-icon> 
                <span class="tooltip hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden" role="tooltip" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(214.8px, -86.4px);" data-popper-placement="top">
                Logout
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </aside>