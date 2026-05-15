const modalTitleBooking = document.getElementById('modalTitleBooking');
const openButtons = document.querySelectorAll('[data-open="modal"]');
const closeButtons = document.querySelectorAll('[data-close="modal"]');
const { isAdmin, isUser, bookingsUrl } = window.appConfig;
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

function fillForm(data) {

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

function setCreateMode() {
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

function setEditMode(id) {
    bookingMode = 'edit';
    currentBookingId = id;

    const form = document.getElementById('bookingForm');
    form.action = `/bookings/${id}`;
    form.method = 'POST';

    removeMethodSpoof();
    addMethodSpoof('PUT');
    document.getElementById('submitButton').innerText = 'Update';
    document.getElementById('modalTitleBooking').innerText = 'Update Schedule';
}
export {
    openModal,
    fillForm,
    resetForm,
    removeMethodSpoof,
    addMethodSpoof,
    resetBadge,
    showBadge,
    setCreateMode,
    setEditMode
};