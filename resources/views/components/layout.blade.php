<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  @vite(['resources/css/app.css'])
  <title>{{ $title }}</title>
  <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="icon" sizes="16x16" href="/storage/images/logo.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>

<body class="h-full antialiased overflow-x-hidden overflow-y-auto scroll-hide" data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">
  
  <div id="overlay"></div>

  <div class="min-h-screen flex p-6 gap-6">

    <x-sidebar></x-sidebar>

    <main class="flex-1">
      <x-header>{{ $title }}</x-header>

    {{ $slot }}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  function deleteItem(e, url){
      e.preventDefault();
      Swal.fire({
          title: "Are you sure?",
          text: "This action cannot be undone!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "Cancel"
      }).then((result) => {
          if (result.isConfirmed) {
              Swal.fire({
                  title: "Deleted!",
                  text: "Your file has been deleted.",
                  icon: "success",
                  timer: 1500,
                  showConfirmButton: false
              }).then(() => {
                  let form = document.createElement('form');
                  form.action = url;
                  form.method = 'POST';
                  form.innerHTML = `
                      @csrf
                      @method('DELETE')
                  `;
                  document.body.appendChild(form);
                  form.submit();
              });
          }
      });
  }
  </script>

  <script>
    const sidebarMobile = document.getElementById('sidebarMobile');
    const btnOpen = document.getElementById('btn-open');
    const btnCloseMobile = document.getElementById('btn-close-mobile');
    const overlay = document.getElementById('overlay');

    btnOpen?.addEventListener('click', () => {
      sidebarMobile.classList.remove('-translate-x-full');
      overlay.style.display = 'block';
    });
    btnCloseMobile?.addEventListener('click', () => {
      sidebarMobile.classList.add('-translate-x-full');
      overlay.style.display = 'none';
    });
    overlay?.addEventListener('click', () => {
      sidebarMobile.classList.add('-translate-x-full');
      overlay.style.display = 'none';
    });

    function openModal() {
      addModal.classList.remove('hidden');
      addModal.classList.add('flex');

      requestAnimationFrame(() => {
        modalContent.classList.remove('scale-90', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
      });
    }

    function closeModal() {
      modalContent.classList.add('scale-90', 'opacity-0');
      modalContent.classList.remove('scale-100', 'opacity-100')

      setTimeout(() => {
        addModal.classList.add('hidden');
        addModal.classList.remove('flex');

        if (document.body.dataset.hasErrors === 'true') {
          window.location.reload();
        }
      }, 300);
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

      if (!input) return;
      input.onchange = null;

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

    document.addEventListener('DOMContentLoaded', function() {
      const openButtons = document.querySelectorAll('[data-open="modal"]');
      const closeButtons = document.querySelectorAll('[data-close="modal"]');
      const addModal = document.getElementById('addModal');
      const modalContent = document.getElementById('modalContent');
      const form = addModal.querySelector('form');
      const submitButton = document.getElementById('submitButton');
      const modalTitleEvent = document.getElementById('modalTitleEvent');
      const modalTitleUser = document.getElementById('modalTitleUser'); 
      const modalTitleDocumentation = document.getElementById('modalTitleDocumentation'); 
      const roleSelect = document.getElementById('role');
      const statusSelect = document.getElementById('status');
      const picturePreview = document.getElementById('picturePreview');
      const posterPreview = document.getElementById('posterPreview');
      const pass = document.getElementById('pass');

      form.addEventListener('submit', function(e){
          console.log('Submit ke:', form.action);
          console.log('Method spoof:', form.querySelector('[name="_method"]')?.value);
      });

      function updateSelectColor(select) {
        if (!select) return;

        if (select.value) {
          select.classList.remove('text-gray-500');
          select.classList.add('text-black');
        } else {
          select.classList.add('text-gray-500');
          select.classList.remove('text-black');
        }
      }
      document.querySelectorAll('select').forEach(select => {
        updateSelectColor(select);

        select.addEventListener('change', function () {
          updateSelectColor(this);
        });
      });

      openButtons.forEach(btn => {
        btn.addEventListener('click', async (e) => {
          if (btn.type === 'submit') return;
          e.preventDefault();
          
          const action = btn.dataset.action;
          const id = btn.dataset.id;
          const type = btn.dataset.type;
          const picturesContainer = document.getElementById('picturesContainer');
          const fileInput = document.getElementById('fileInput');

          openModal();
          
          if (type === 'event') {
            removeMethodSpoof();
            
            initImageInput({
              inputId: 'posterInput',
              previewId: 'posterPreview',
              errorId: 'posterError'
            });

            if (action === 'create') {
              form.action = '/events';
              form.reset();
              removeMethodSpoof();
              submitButton.innerText = 'Add';
              modalTitleEvent.innerText = 'Add Event';
              posterPreview.src = '';
              posterPreview.classList.add('hidden');
              document.getElementById('posterInput').value = '';
            }
            if (action === 'edit' && id) {
              form.action = `/events/${id}`;
              removeMethodSpoof();
              addMethodSpoof('PUT');
  
              modalTitleEvent.innerText = 'Edit Event';
              submitButton.innerText = 'Update';
              
              const res = await fetch(`/events/${id}/edit`);
              const data = await res.json();

              const eventform = document.querySelector('#eventForm');
              fillForm(data, eventform);

              posterPreview.src = '';
              posterPreview.classList.add('hidden');

              if (data.poster) {
                posterPreview.src = `/storage/images/${data.poster}`;
                posterPreview.classList.remove('hidden');
              }
            }
          }
          
          if (type === 'user') {

            if (action === 'create') {
              form.action = '/users';
              form.reset();
              removeMethodSpoof();
              submitButton.innerText = 'Add';
              modalTitleUser.innerText = 'Add User';
              pass.classList.add('flex');
              updateSelectColor(roleSelect);
              updateSelectColor(statusSelect);
            }
            if (action === 'edit' && id) {
              form.action = `/users/${id}`;
              addMethodSpoof('PUT');
  
              modalTitleUser.innerText = 'Edit User';
              submitButton.innerText = 'Update';
              pass.classList.add('hidden');
              
              const res = await fetch(`/users/${id}/edit`);
              const data = await res.json();

              const userform = document.querySelector('#userForm');
              fillForm(data, userform);

              updateSelectColor(roleSelect);
              updateSelectColor(statusSelect);
            }
          }

          function addPicturePreview(src, file = null) {
            const picturesContainer = document.getElementById('picturesContainer');

            const wrapper = document.createElement('div');
            wrapper.classList.add('relative');

            const img = document.createElement('img');
            img.src = src;
            img.classList.add('w-50', 'h-47','object-cover','rounded-lg');

            const btn = document.createElement('button');
            btn.innerHTML = '&times;';
            btn.classList.add(
                'absolute','top-0','right-0',
                'text-red-700',
                'w-8','h-8',
                'flex','items-center','justify-center'
            );

            btn.onclick = () => {
                wrapper.remove();
                if (file) {
                    newFiles = newFiles.filter(f => f !== file);
                }
            };

            wrapper.appendChild(img);
            wrapper.appendChild(btn);
            picturesContainer.appendChild(wrapper);
        }


          let newFiles = [];
          let removedExisting = [];
          if (type === 'documentation') {
            fileInput.onchange = null;
            form.onsubmit = null;
            picturesContainer.onclick = null;
            
            initImageInput({
              inputId: 'fileInput',
              previewId: 'picturesPreview',
              errorId: 'picturesError'
            });

            if (action === 'create') {
                form.action = '/documentations';
                form.method = 'POST';
                const methodInput = form.querySelector('[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }
                form.reset();
                newFiles = [];
                removedExisting = [];
                removeMethodSpoof();

                submitButton.innerText = 'Add';
                modalTitleDocumentation.innerText = 'Add Post';

                const picturesContainer = document.getElementById('picturesContainer');
                picturesContainer.innerHTML = '';


                fileInput.onchange = (e) => {

                    const files = Array.from(e.target.files);

                    files.forEach(file => {
                        newFiles.push(file);

                        const reader = new FileReader();
                        reader.onload = (event) => {
                            addPicturePreview(event.target.result, file);
                        };
                        reader.readAsDataURL(file);
                    });
                };
                form.onsubmit = (e) => {
                    e.preventDefault();

                    const dataTransfer = new DataTransfer();

                    newFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    fileInput.files = dataTransfer.files;
                    console.log(newFiles);
                    console.log(fileInput.files);

                    form.submit();
                };
            }
            if (action === 'edit' && id) {
              form.action = `/documentations/${id}`;
              form.method = 'POST';
              addMethodSpoof('PUT');
              
              newFiles = [];
              removedExisting = [];

              modalTitleDocumentation.innerText = 'Edit Content';
              submitButton.innerText = 'Update';

              const res = await fetch(`/documentations/${id}/edit`);
              const data = await res.json();

              const documentationform = document.querySelector('#documentationForm');
              fillForm(data, documentationform);


              picturesContainer.innerHTML = '';
              fileInput.value = '';

              if (data.pictures && data.pictures.length > 0) {

                  data.pictures.forEach((pic, index) => {

                      const wrapper = document.createElement('div');
                      wrapper.classList.add('relative');
                      wrapper.dataset.existing = true;
                      wrapper.dataset.index = index;

                      const img = document.createElement('img');
                      img.src = `/storage/${pic}`;
                      img.classList.add('w-50', 'h-47','object-cover','rounded-lg');

                      const btn = document.createElement('button');
                      btn.innerHTML = '&times;';
                      btn.classList.add(
                          'delete-btn',
                          'absolute','top-0','right-0',
                          'text-red-700',
                          'w-8','h-8',
                          'flex','items-center','justify-center'
                      );

                      wrapper.appendChild(img);
                      wrapper.appendChild(btn);

                      picturesContainer.appendChild(wrapper);
                  });

              }
              fileInput.onchange = (e) => {

                  const files = Array.from(e.target.files);
                  files.forEach(file => {
                      newFiles.push(file);
                      const reader = new FileReader();
                      reader.onload = (event) => {
                          addPicturePreview(event.target.result, file);
                      };
                      reader.readAsDataURL(file);
                  });
                  fileInput.value = '';
              };

              picturesContainer.onclick = (e) => {
                  if (e.target.classList.contains('delete-btn')) {
                      const wrapper = e.target.parentElement;
                      if (wrapper.dataset.existing) {
                          removedExisting.push(wrapper.dataset.index);
                      }
                      wrapper.remove();
                  }
              };
              form.onsubmit = () => {

                  const dataTransfer = new DataTransfer();

                  newFiles.forEach(file => {
                      dataTransfer.items.add(file);
                  });

                  fileInput.files = dataTransfer.files;

                  const old = form.querySelector('input[name="removedExisting"]');
                  if (old) old.remove();

                  let removedInput = document.createElement('input');
                  removedInput.type = 'hidden';
                  removedInput.name = 'removedExisting';
                  removedInput.value = JSON.stringify(removedExisting);

                  form.appendChild(removedInput);
              };
            }
          }
        });
      });

      closeButtons.forEach(btn => {
        btn.addEventListener('click', closeModal);
      });

      function addMethodSpoof(method) {
        removeMethodSpoof();
        form.insertAdjacentHTML(
          'beforeend',
          `<input type="hidden" name="_method" value="${method}">`
        );
      }

      function removeMethodSpoof() {
        form.querySelector('[name="_method"]')?.remove();
      }
      window.fillForm = function(data, form) {
        for (const key in data) {
            if (['poster', 'validate', 'picture'].includes(key)) continue;

            const field = form.querySelector(`[name="${key}"]`);
            if (field) field.value = data[key];

            if (field instanceof HTMLSelectElement) {
                field.classList.remove('text-gray-500');
                field.classList.add('text-black');
            }
        }
      };
    });

    const calendarSwitch = document.getElementById('calendarSwitch');
    const tableSwitch = document.getElementById('tableSwitch');
    const calendarView = document.getElementById('calendarView');
    const tableView = document.getElementById('tableView');
    const postSwitch = document.getElementById('postSwitch');
    const postView = document.getElementById('postView');

    function showCalendar() {
      if (calendarView) {
        calendarView.classList.remove('hidden');
      }
      if (calendarSwitch) {
        calendarSwitch.classList.add('text-gray-500');
      }
      tableView.classList.add('hidden');
      tableSwitch.classList.remove('text-gray-500');
      localStorage.setItem('viewMode', 'calendar');
      setTimeout(() => {
        if (typeof calendar !== "undefined") {
          calendar.updateSize();
        }
      },100);
    }

    function showPost() {
      if (postView) {
        postView.classList.remove('hidden');
        document.querySelectorAll('.cardPost').forEach(el => {
            el.classList.add('hidden')
        })
      }
      if (postSwitch) {
        postSwitch.classList.add('text-gray-500');
      }
      if (tableView) {
        tableView.classList.add('hidden');
      }
      if (tableSwitch) {
        tableSwitch.classList.remove('text-gray-500');
      }
      localStorage.setItem('viewMode', 'post');
      setTimeout(() => {
        if (typeof post !== "undefined") {
          post.updateSize();
        }
      },100);
    }

    function showTable() {
      if (tableView) {
        tableView.classList.remove('hidden');
        tableSwitch.classList.add('text-gray-500');
      } 
      if (calendarView) {
        calendarView.classList.add('hidden');
        calendarSwitch.classList.remove('text-gray-500');
      } 
      if (postView) {
        postView.classList.add('hidden');
      } 
      if (postSwitch) {
        postSwitch.classList.remove('text-gray-500');
      } 
      localStorage.setItem('viewMode', 'table');
    }

    if (calendarSwitch) {
        calendarSwitch.addEventListener('click', showCalendar);
    }
    if (postSwitch) {
        postSwitch.addEventListener('click', showPost);
    }
    if (tableSwitch) {
      tableSwitch.addEventListener('click', showTable);
    }

    const viewMode = localStorage.getItem('viewMode');

    if (viewMode === 'calendar') {
      showCalendar();
    }else if (viewMode === 'post') {
      showPost();
    }else {
      showTable();
    }
  </script>
</body>
</html>
