@props(['slots', 'bookedSlots'])
@if(isset($slots) && count($slots))
    <div {{ $attributes->merge(['class' => 'grid grid-cols-4 md:grid-cols-5 gap-4']) }}>
        @foreach($slots as $slot)

        @php
            $isBooked = in_array($slot['start'], $bookedSlots ?? []);
        @endphp

        <button 
            type="button"
            class="bg-gray-600 text-xs rounded-2xl text-gray-200 py-3 text-center hover:bg-gray-400 transition shadow-lg hover:shadow-md slot-btn"
            data-start="{{ $slot['start'] }}"
            data-end="{{ $slot['end'] }}">
            <div>{{ $slot['start'] }} - {{ $slot['end'] }}</div>
            <div class="text-xs text-gray-300">1 jam</div>
        </button>

    @endforeach
    </div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {

    const buttons = document.querySelectorAll('.slot-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {

            const clickedHour = parseInt(this.dataset.start.split(':')[0]);

            if (this.classList.contains('ring-2')) {
                this.classList.remove('ring-2', 'ring-[#6dadbe]', 'bg-gray-300');
                this.classList.add('bg-bg-gray-600');
                return;
            }

            this.classList.remove('bg-bg-gray-600');
            this.classList.add('bg-gray-300', 'ring-2', 'ring-[#6dadbe]');

            validateSequential(clickedHour);
        });
    });

    function validateSequential(lastClickedHour) {

        const activeButtons = document.querySelectorAll('.slot-btn.ring-2');

        if (activeButtons.length <= 1) return;

        let hours = [];

        activeButtons.forEach(btn => {
            let hour = parseInt(btn.dataset.start.split(':')[0]);
            hours.push(hour);
        });

        hours.sort((a, b) => a - b);

        for (let i = 1; i < hours.length; i++) {
            if (hours[i] !== hours[i - 1] + 1) {

                buttons.forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-[#6dadbe]', 'bg-gray-300');
                    btn.classList.add('bg-gray-600');
                });

                buttons.forEach(btn => {
                    let hour = parseInt(btn.dataset.start.split(':')[0]);
                    if (hour === lastClickedHour) {
                        btn.classList.remove('bg-gray-600');
                        btn.classList.add('bg-gray-600', 'ring-2', 'ring-[#6dadbe]');
                    }
                });

                return;
            }
        }
    }

});
</script>