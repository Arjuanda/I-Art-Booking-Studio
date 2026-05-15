<script>
    const modalTitleBooking = document.getElementById('modalTitleBooking');
    const openButtons = document.querySelectorAll('[data-open="modal"]');
    const closeButtons = document.querySelectorAll('[data-close="modal"]');
    const isAdmin = @json(auth()->user()->role === 'admin');
    const isUser = @json(auth()->user()->role === 'user');
    let isAdding = false;
    
    function openModal() {
        const modal = document.getElementById('addModal'); 
        const content = document.getElementById('modalContent'); 

        modal.classList.remove('hidden'); 
        modal.classList.add('flex'); 

        requestAnimationFrame(() => { 
            content.classList.remove('scale-90', 'opacity-0'); 
            content.classList.add('scale-100', 'opacity-100'); 
        }); 
    }

    function fillform(data) {

        const dateInput = document.getElementById('date');
        dateInput.value = data.date;
        dateInput.dispatchEvent(new Event('input'));

        const startInput = document.getElementById('start');
        startInput.value = data.start;
        startInput.dispatchEvent(new Event('input'));

        const endInput = document.getElementById('end');
        endInput.value = data.end;
        endInput.dispatchEvent(new Event('input'));

        const badge = document.getElementById('statusBadge');
        badge.textContent = data.status;
        badge.dataset.value = data.status;
    }

    function resetForm() {
        const dateInput = document.getElementById('date');
        const startInput = document.getElementById('start');
        const endInput = document.getElementById('end');

        dateInput.value = '';
        startInput.value = '';
        endInput.value = '';

        dateInput.dispatchEvent(new Event('input'));
        startInput.dispatchEvent(new Event('input'));
        endInput.dispatchEvent(new Event('input'));
    }

    function removeMethodSpoof() {
        const spoof = document.querySelector('input[name="_method"]');
        if (spoof) spoof.remove();
    }
    
    function addMethodSpoof(method = 'PUT') {
        removeMethodSpoof();
        
        const form = document.getElementById('bookingForm');
        if (!form) return;
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = method;
        
        form.appendChild(input);
    }

    function resetBadge() {
    const badge = document.getElementById('statusBadge');
    if (badge) {
        badge.style.display = 'none';
        badge.innerText = '';
    }
    }

    function showBadge(status) {
        const badge = document.getElementById('statusBadge');
        badge.style.display = 'inline-block';

        const normalized =
            status === 'on going' ? 'on-going' : status;

        badge.dataset.value = normalized;

        badge.classList.remove(
            'approved', 'pending', 'rejected', 'on-going'
        );

        badge.classList.add('badge-status', normalized);

        badge.innerText =
            normalized === 'on-going'
                ? 'On Going'
                : normalized;
    }

          function initImageInput({
            inputId,
            previewId,
            errorId,
            allowedTypes = ['image/jpeg', 'image/png']
          }) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const error = document.getElementById(errorId);

            if (!input || input.dataset.bound) return;

            input.dataset.bound = 'true';

            input.addEventListener('change', function () {
              const file = this.files[0];
              if (!file) return;

              if (!allowedTypes.includes(file.type)) {
                if (error) error.textContent = 'File harus berupa gambar (JPG / PNG)';
                this.value = '';
                if (preview) preview.classList.add('hidden');
                return;
              }

              if (error) error.textContent = '';

              const reader = new FileReader();
              reader.onload = e => {
                if (preview) {
                  preview.src = e.target.result;
                  preview.classList.remove('hidden');
                }
              };
              reader.readAsDataURL(file);
            });
          }

    async function setCreateMode() {
        bookingMode = 'create';
        currentBookingId = null;

        const form = document.getElementById('bookingForm');
        form.action = '/bookings';
        form.method = 'POST';

        removeMethodSpoof?.();
        document.getElementById('submitButton').innerText = 'Add';
        document.getElementById('modalTitleBooking').innerText = 'Add Schedule';

        form.reset();    
    }

    async function setEditMode(id) {
        bookingMode = 'edit';
        currentBookingId = id;

        const validatePreview = document.getElementById('validatePreview');

        const form = document.getElementById('bookingForm');
        form.action = `/bookings/${id}`;
        form.method = 'POST';

        removeMethodSpoof();
        addMethodSpoof('PUT');
        document.getElementById('submitButton').innerText = 'Update';
        document.getElementById('modalTitleBooking').innerText = 'Update Schedule';

        initImageInput({
        inputId: 'validateInput',
        previewId: 'validatePreview',
        errorId: 'validateError'
        });

        const res = await fetch(`/bookings/${id}/edit`);
        const data = await res.json();

        fillform(data);
        document.getElementById('userName').value = data.user?.name ?? '-';
        if (validatePreview) {
            if (data.validate) {
                validatePreview.src = `/storage/images/${data.validate}`;
                validatePreview.classList.remove('hidden');
            } else {
                validatePreview.src = '';
                validatePreview.classList.add('hidden');
            }
        }

        return data;
    }

    async function openEditFromTable(id) {
        isAdding = false;

        const data = await setEditMode(id);

        const displayStatus = data.status;
        showBadge(displayStatus);
        showButtons(data);

        openModal();
    }

    function openCreateFromTable() {
        isAdding = true;

        resetBadge();
        setCreateMode();
        showButtons();

        openModal();
    }

    function generateTimeOptions(startHour, endHour) {
        const times = [];
        for (let hour = startHour; hour <= endHour; hour++) {
            times.push(String(hour).padStart(2, '0') + ':00');
        }
        return times;
    }

    function isMobile() {
        return window.innerWidth < 640;
    }

    function showButtons(data) {
        const userBtn = document.getElementById('userButton');
        const adminBtn = document.getElementById('adminButton');
        const deleteBtn = document.getElementById('deleteButton');
        const badge = document.getElementById('statusBadge');


        if (isAdding) {
            userBtn?.classList.remove('hidden');
            userBtn?.classList.add('flex');
            return;
        }

        const pending    = data?.status === 'pending';
        const approved = data?.status === 'approved';
        const rejected   = data?.status === 'rejected';
        const finished   = data?.status === 'finished';
        const onGoing = badge?.dataset.value === 'on-going';

        if ((isAdmin && (approved || onGoing || finished)) || (isUser && (approved || onGoing || finished))) {
            userBtn?.classList.add('hidden');
            userBtn?.classList.remove('flex');
            adminBtn?.classList.add('hidden');
            adminBtn?.classList.remove('flex');
            document.getElementById('modalTitleBooking').innerText = 'Schedule';
        } else if (isAdmin && pending) {
            adminBtn?.classList.remove('hidden');
            adminBtn?.classList.add('flex');
            userBtn?.classList.add('hidden');
            userBtn?.classList.remove('flex');
        } else if (isUser && pending) {
            adminBtn?.classList.remove('flex');
            adminBtn?.classList.add('hidden');
            userBtn?.classList.add('flex');
            userBtn?.classList.remove('hidden');
            deleteBtn?.classList.remove('hidden');        
        } else if (rejected){
            userBtn?.classList.add('hidden');
            userBtn?.classList.remove('flex');
            adminBtn?.classList.add('hidden');
            adminBtn?.classList.remove('flex');
            document.getElementById('modalTitleBooking').innerText = 'Schedule';            
        } else {
            userBtn?.classList.remove('hidden');
            userBtn?.classList.add('flex');
            adminBtn?.classList.add('hidden');
            adminBtn?.classList.remove('flex');
        }
    }

    function getDisplayStatus(event) {
        const status = event.extendedProps?.status;
        if (status !== 'approved') return status;

        const now = new Date();
        const start = new Date(event.start);
        const end = new Date(event.end);

        if (now >= start && now < end) {
            return 'on-going';
        }

        return event.extendedProps.status;
    }
   
    function disableUsedTime(date) {
        window.disabledTimes = [];

        const events = calendar.getEvents();

        events.forEach(event => {
            if (!event.start || !event.end) return;

            if (event.extendedProps?.status === 'rejected') return;
            if (!event.startStr.startsWith(date)) return;

            const startHour = event.start.getHours();
            const endHour   = event.end.getHours();

            for (let h = startHour; h < endHour - 1; h++) {
                window.disabledTimes.push(
                    String(h).padStart(2, '0') + ':00'
                );
            }
        });
    }

    function getCalendarOptions() {
        return {
            height: isMobile() ? '70vh' : 900,
            expandRows: true,
            fixedWeekCount: true,

            headerToolbar: {
                left: isMobile()
                    ? 'prev today'
                    : 'prev today',
                center: 'title',
                right: isMobile()
                    ? 'next'
                    : 'next'
            },
            initialView: 'dayGridMonth',
            dayMaxEvents: isMobile() ? 0 : false,
            dayMaxEventRows: isMobile() ? 0 : false,

            moreLinkContent: () => ({
                html: `<i class="fa-solid fa-eye"></i>`
            }),
            eventClassNames(arg) {
                return [
                    arg.event.extendedProps.displayStatus || arg.event.extendedProps.status
                ];
            },
            eventDidMount: function(info) {
                const dot = info.el.querySelector('.fc-event');

                const now = new Date();

                const start = new Date(info.event.start);
                const end   = new Date(info.event.end);

                const el = info.el;
                const displayStatus = getDisplayStatus(info.event);
                info.event.setExtendedProp('displayStatus', displayStatus);

                if (info.el.closest('.fc-more-popover')) {
                    const start = info.event.start;
                    const end = info.event.end;

                    if (!start || !end) return;

                    const startText = start.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const endText = end.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const titleEl = info.el.querySelector('.fc-event-title');
                    if (titleEl) {
                        titleEl.innerHTML = `
                            <span class="text-xs text-gray-500">${startText}</span>
                            <span class="text-xs text-gray-500"> - </span>
                            <span class="text-xs text-gray-500">${endText}</span>
                        `;
                    }
                }
            },

            dateClick: function(info) {
                resetForm();
                setCreateMode();
                resetBadge();
                isAdding = true;
                document.getElementById('adminButton')
                    ?.classList.add('hidden');
                document.getElementById('date').value = info.dateStr;
                document.getElementById('date').dispatchEvent(new Event('input'));
                disableUsedTime(info.dateStr);
                showButtons();
                openModal();
            }, 
            eventClick: async function (info) {
                isAdding = false;
                
                setEditMode(info.event.id);
                
                const id = info.event.id;
                
                if (!id) return;
                
                const res = await fetch(`/bookings/${id}/edit`);
                const data = await res.json();
                const pending = data.status === 'pending';
                const approved = data.status === 'approved';

                const displayStatus = getDisplayStatus(info.event);

                setEditMode(info.event.id, data.status);
                
                fillform(data);

                showBadge(displayStatus);
                showButtons({
                    ...data,
                    status: displayStatus
                });
                openModal(); 
            },
            select:function(info) { 

            },
            eventOrder: 'start',
            eventOverlap:false,
            editable: true, 
            selectable: true,
            displayEventTime: false,
            events: `{{ route('bookings.list') }}`,
        };
    }

    function updateCalendarForScreen() {
        calendar.setOption('height', isMobile() ? '70vh' : 900);
        calendar.setOption('dayMaxEvents', isMobile() ? 0 : false);
        calendar.setOption('dayMaxEventRows', isMobile() ? 0 : false);

        calendar.setOption('headerToolbar', {
            left: isMobile()
                    ? 'prev today'
                    : 'prev today',
                center: 'title',
                right: isMobile()
                    ? 'next'
                    : 'next'
        });
    }

    function debounce(fn, delay) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    let calendar;

    window.disabledTimes = [];

    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, getCalendarOptions());
        calendar.render();

    setInterval(() => {
        calendar.getEvents().forEach(event => {
            const displayStatus = getDisplayStatus(event);
            event.setExtendedProp('displayStatus', displayStatus);
        });

        calendar.rerenderEvents();
    }, 60 * 1000);


        let lastIsMobile = isMobile();
        window.addEventListener('resize', debounce(() => {
            const nowMobile = isMobile();
            if (nowMobile !== lastIsMobile) {
                lastIsMobile = nowMobile;
                updateCalendarForScreen();
            }
        }, 200));
    });
</script>

<div {{ $attributes }}>
    <div class="-my-2 -mx-0 sm:-mx-0 md:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 overflow-x-auto">
            <div class="overflow-hidden px-6">
                <div class="px-2 py-5" id='calendar'></div>
            </div>
        </div>
    </div>
</div>
                