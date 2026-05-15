@props(['active' => false])

<a {{ $attributes }} class="group {{ $active ? 'active-gradient' : ''}} flex items-center gap-3 py-2 px-3 rounded-lg hover-gradient" aria-current="{{ $active ? 'page' : false }}">
    <div class="h-9 w-9 flex items-center justify-center text-slate-900">{{ $icon ??'' }}</div>
        <div class="sidebar-label">
            <div>{{ $slot }}</div>
        </div>
</a>