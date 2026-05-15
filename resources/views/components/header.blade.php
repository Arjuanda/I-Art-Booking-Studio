      <!-- topbar -->
      <header id="topbar" class="flex items-center justify-between sticky top-0 z-30 mb-2 bg-white rounded-2xl shadow-lg px-6 py-1.5 ">
        <div class="flex">
          <button id="btn-open" class="flex items-center lg:hidden">
            <iconify-icon icon="solar:hamburger-menu-line-duotone" class="text-xl p-3 text-black hover-gradient rounded-full cursor-pointer"></iconify-icon>
          </button>
          <h1 class="text-xl font-bold flex items-center">{{ $slot }}</h1>
        </div>

        <div class="flex items-center gap-3">
          <div class="flex items-center gap-3 py-2 px-3">
            <div class="hidden md:block">
              <h6 class="font-bold text-dark">{{ explode(' ', auth()->user()->name)[0] }}</h6>
              <p class="text-sm leading tight mb-0 text-link ">{{ auth()->user()->role }}</p>
            </div>
          </div>
          <div class="ms-auto hs-tooltip hs-tooltip-toggle">
            <a href="/logout" class="text-link hover:text-primary hover:text-blue-400"><iconify-icon icon="solar:logout-line-duotone" class="text-3xl"></iconify-icon> 
              <span class="tooltip hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible hidden" role="tooltip" style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate(214.8px, -86.4px);" data-popper-placement="top">
              Logout
              </span>
            </a>
          </div>
        </div>
      </header>