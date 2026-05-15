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

      form.addEventListener('submit', function(e){
          console.log('Submit ke:', form.action);
          console.log('Method spoof:', form.querySelector('[name="_method"]')?.value);
      });


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
            }
            if (action === 'edit' && id) {
              form.action = `/events/${id}`;
              addMethodSpoof('PUT');
  
              modalTitleEvent.innerText = 'Edit Event';
              submitButton.innerText = 'Update';
              
              const res = await fetch(`/events/${id}/edit`);
              const data = await res.json();
  
              fillForm(data);

              posterPreview.src = '';
              posterPreview.classList.add('hidden');

              if (data.poster) {
                posterPreview.src = `/storage/images/${data.poster}`;
                posterPreview.classList.remove('hidden');
              }
            }
          }
          
          if (type === 'user') {

            initImageInput({
              inputId: 'profileInput',
              previewId: 'profilePreview',
              errorId: 'profileError'
            });

            if (action === 'create') {
              form.action = '/users';
              form.reset();
              removeMethodSpoof();
              submitButton.innerText = 'Add';
              modalTitleUser.innerText = 'Add User';
              profilePreview.src = '';
              profilePreview.classList.add('hidden');
              updateSelectColor(roleSelect);
              updateSelectColor(statusSelect);
            }
            if (action === 'edit' && id) {
              form.action = `/users/${id}`;
              addMethodSpoof('PUT');
  
              modalTitleUser.innerText = 'Edit User';
              submitButton.innerText = 'Update';
              
              const res = await fetch(`/users/${id}/edit`);
              const data = await res.json();

              fillForm(data);

              profilePreview.src = '';
              profilePreview.classList.add('hidden');

              if (data.profile_image) {
                profilePreview.src = `/storage/images/${data.profile_image}`;
                profilePreview.classList.remove('hidden');
              }

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

              fillForm(data);

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

      function fillForm(data) {
        for (const key in data) {
          if (key === 'poster') continue;
          if (key === 'profile_image') continue;
          if (key === 'validate') continue;
          if (key === 'picture') continue;
          const field = form.querySelector(`[name="${key}"]`);
          if (field) field.value = data[key];

          if (field instanceof HTMLSelectElement) {
            field.classList.remove('text-gray-500');
            field.classList.add('text-black');
          }
        }
      }
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


    applySearchStyle();
    new MutationObserver(applySearchStyle).observe(html, { attributes: true, attributeFilter: ['class'] });

    // ---------- Charts (Chart.js) ----------
    document.addEventListener('DOMContentLoaded', () => {
      // Bar-like active users (stylized)
      const barCtx = document.getElementById('barChart').getContext('2d');
      new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
          datasets: [{
            label: 'Active',
            data: [40, 20, 60, 35, 80, 55, 90],
            backgroundColor: '#1f2937',
            borderRadius: 8,
            barThickness: 12
          }]
        },
        options: {
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: getComputedStyle(document.body).color } },
            y: { grid: { color: 'rgba(15,23,42,0.04)' }, ticks: { color: getComputedStyle(document.body).color } }
          }
        }
      });

      // Line sales
      const lineCtx = document.getElementById('lineChart').getContext('2d');
      const gradient = lineCtx.createLinearGradient(0,0,0,300);
      gradient.addColorStop(0, 'rgba(236,72,153,0.18)');
      gradient.addColorStop(1, 'rgba(123,97,255,0.04)');

      new Chart(lineCtx, {
        type: 'line',
        data: {
          labels: ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
          datasets: [
            {
              label: 'Sales A',
              data: [20,80,180,150,320,260,300,250,380],
              borderColor: '#ec4899',
              backgroundColor: gradient,
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              borderWidth: 3
            },
            {
              label: 'Sales B',
              data: [40,60,110,120,180,220,240,210,260],
              borderColor: '#4b5563',
              backgroundColor: 'rgba(75,85,99,0.04)',
              tension: 0.4,
              fill: true,
              pointRadius: 0,
              borderWidth: 3
            }
          ]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: getComputedStyle(document.body).color } },
            y: { grid: { color: 'rgba(15,23,42,0.04)' }, ticks: { color: getComputedStyle(document.body).color } }
          }
        }
      });

      // Update chart text color on theme change
      const updateChartColors = () => {
        document.querySelectorAll('canvas').forEach(c => {
          if (c && c.chart) {
            // Chart.js v4 attaches instance to canvas.chart in some contexts — safe guard
            try { c.chart.update(); } catch (e) {}
          }
        });
      };
      // observe html class changes
      new MutationObserver(() => updateChartColors()).observe(html, { attributes: true, attributeFilter: ['class'] });
    });