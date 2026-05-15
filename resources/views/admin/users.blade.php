<x-layout>
    
    <div class="flex justify-between items-center mb-4">
        <x-slot:title>{{ $title }}</x-slot:title>
    </div>
    
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between top-0 z-0 mb-2 bg-white shadow-lg rounded-2xl px-6 py-5 ">
        <div class="flex items-center gap-2 w-full">
            <div class="relative shrink-0 ml-auto">
                <a href="javascript:void(0)" id="openModal" data-open="modal" data-type="user" data-action="create" class="inline-block px-4 py-4 sm:py-2 bg-blue-500 rounded-4xl text-white hover:bg-blue-700 gap-2">
                    <div class="flex justify-between items-center gap-2">
                        <i class="ti ti-users text-white text-lg leading-none"></i>
                        <p class="hidden sm:flex">Add {{ $title }}</p>
                    </div>
                </a>
            </div>
        </div>
        <div id="addModal" class="fixed inset-0 max-w- transition-transition bg-black/60 duration-300 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center z-50">
            <div id="modalContent" class="bg-white p-6 max-w-115 rounded-2xl transform {{ $errors->any() ? 'scale-100 opacity-100' : 'scale-90 opacity-0' }} transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <h3 id="modalTitleUser" class="text-lg font-semibold items-center">Add User</h3>
                    <button data-close="modal" data-reset="true" class="items-center px-2 text-black rounded-full hover:bg-gray-200 cursor-pointer">X</button>
                </div> 
                <div class="flex flex-col">
                    <form action="" id="userForm" method="POST" class="my-4" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-start gap-x-4 mb-2">
                            <div class="flex flex-col">
                                <input type="text" placeholder="Badge" value="{{ old('badge') }}" inputmode="numeric" name="badge" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                @error('badge')
                                    <div class="text-red-500 text-sm max-w-50">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex flex-col">            
                                <input type="text" placeholder="Name" value="{{ old('name') }}" name="name" class=" date-picker:cursor-pointer w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                @error('name')
                                    <div class="text-red-500 text-sm max-w-50">{{ $message }}</div>
                                @enderror
                            </div>  
                        </div>
                        <div class="flex items-start gap-x-4 mb-2">
                            <div class="flex flex-col">
                                <input type="text" placeholder="Contact" value="{{ old('contact') }}" inputmode="numeric" name="contact" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                @error('contact')
                                    <div class="text-red-500 text-sm max-w-50">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex flex-col">            
                                <input type="email" placeholder="Email" value="{{ old('email') }}" name="email" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                @error('email')
                                    <div class="text-red-500 text-sm max-w-50">{{ $message }}</div>
                                @enderror
                            </div>  
                        </div>
                        <div class="flex items-start gap-x-4 mb-2">
                            <div class="flex flex-col flex-1">
                                <select id="role" name="role" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                                    <option value="admin" class="text-black" {{ old('role') == 'admin' ? 'selected' : '' }}>admin</option>
                                    <option value="karyawan" class="text-black" {{ old('role') == 'karyawan' ? 'selected' : '' }}>karyawan</option>
                                </select>
                                @error('role')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="flex flex-col flex-1">
                                <select id="status" name="status" class="w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                                    <option value="pending" class="text-black" {{ old('status') == 'pending' ? 'selected' : '' }}>pending</option>
                                    <option value="active" class="text-black" {{ old('status') == 'active' ? 'selected' : '' }}>active</option>
                                    <option value="banned" class="text-black" {{ old('status') == 'banned' ? 'selected' : '' }}>banned</option>
                                </select>
                                @error('status')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>  
                        </div>
                        <div class="flex items-start gap-x-4 mb-2">
                            <div class="flex flex-col">
                                <p id="pass" class="pl-3">Password : Password123</p>
                                <div id="passwordSection">
                                    <button type="button" id="inputSwitch2" class="text-blue-500 pl-2 cursor-pointer hover:text-blue-700 w-full sm:items-center">Ubah Password?</button>
                                    <input type="password" id="passwordField2" placeholder="New Password" name="password" class="hidden w-full my-2 py-2 px-2 border border-gray-600/60 focus:border-blue-400 focus:ring-1 focus:ring-blue-300/30 focus:outline-none rounded-lg">
                                    @error('password')
                                        <div class="text-red-500 text-sm max-w-50">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            <div class="flex justify-end gap-2">
                                <button type="submit" id="submitButton" class="text-center my-2 bg-green-400 hover:bg-green-500/100 py-2 px-4 rounded-4xl cursor-pointer">Add</button>
                                <button type="button" data-close="modal" data-reset="true" class="text-center my-2 bg-red-100 hover:bg-red-300 text-red-400 py-2 px-4 rounded-4xl cursor-pointer">Discard</button>
                            </div>
                    </form>
                </div>           
            </div>
        </div>
    </header>

    <div class="mt-6 hidden sm:flex sm:flex-col shadow-lg sm:rounded-2xl">
        <div class="-my-2 -mx-4 sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8 overflow-x-auto">
                <div class="overflow-hidden md:rounded-2xl px-6 bg-white">
                    <table class="min-w-full divide-y table-fixed divide-gray-300 bg-transparent ">
                        <thead class="bg-transparent pt-8">
                            <tr> 
                               <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold pt-8 w-40">Badge</th>
                               <th scope="col" class="hidden sm:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Name</th>
                               <th scope="col" class="hidden md:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40">Contact</th>
                               <th scope="col" class="hidden lg:table-cell py-3.5 px-3 text-left text-sm font-semibold pt-8 w-60 truncate">Email</th> 
                               <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Role</th> 
                               <th scope="col" class="py-3.5 px-3 text-left text-sm font-semibold pt-8 w-40 truncate">Status</th> 
                               <th scope="col" class="py-3.5 px-3 text-right text-sm pl-3 pr-8 font-semibold pt-8 w-10">Action</th>
                            </tr>
                        </thead>
                        @forelse ($data as $item)
                        <tbody class="bg-transparent">
                            <tr>
                                <td class="w-full sm:w-auto max-w-0 sm:max-w-none whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium sm:pl-6 truncate">{{ $item ['badge'] }}
                                    <dl class="lg:hidden font-normal">
                                        <dt class="sr-only sm:hidden">Name</dt>
                                        <dd class="sm:hidden text-gray-700 mt-1 truncate">{{ $item ['name'] }}</dd>
                                        <dt class="sr-only">Contact</dt>
                                        <dd class="md:hidden text-gray-500 mt-1 md:text-gray-700 truncate">{{ $item ['contact'] }}</dd>
                                        <dt class="sr-only">Email</dt>
                                        <dd class="text-gray-500 mt-1 sm:text-gray-500 truncate">{{ $item ['email'] }}</dd>
                                    </dl>
                                    </td>
                                <td class="hidden sm:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{Str::limit( $item ['name'],50) }}</td>
                                <td class="hidden md:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{ $item ['contact'] }}</td>
                                <td class="hidden lg:table-cell whitespace-nowrap py-4 pl-4  text-sm text-gray-500">{{Str::limit( $item ['email'],50) }}</td>
                                <td class="whitespace-nowrap pl-2">
                                    <span class="badge-role" data-value="{{ $item ['role'] }}">{{ $item ['role'] }}</span>
                                </td>
                                <td class="whitespace-nowrap pl-2">
                                    <span class="badge-status" data-value="{{ $item ['status'] }}">{{ $item ['status'] }}</span>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 space-x-1">
                                    <a href="javascript:void(0)" data-open="modal" data-type="user" data-action="edit" data-id="{{ $item->id }}" class="btn-edit text-indigo-600 hover:text-indigo-900">
                                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-3 z-0"></iconify-icon>
                                    </a>
                                    @if (!($user->id === $item->id && $item->role === 'admin'))
                                        <a href="#"
                                        class="text-red-600 hover:text-red-900"
                                        onclick="deleteItem(event, '{{ route('users.destroy', $item->id) }}')">
                                            <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md text-black p-1"></iconify-icon>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                                @empty
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @forelse ($data as $item)
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-1 sm:hidden">
        <div class="shadow-lg relative flex items-center space-x-3 rounded-2xl bg-white px-6 py-5">
            <div class="min-w-0 flex-1">
                <div class="flex flex-row items-center space-x-3">
                    <p class="truncate text-sm font-medium">{{ $item ['name'] }}</p>
                    <span class="inline-block flex-shrink-0 badge-role" data-value="{{ $item ['role'] }}">{{ $item ['role'] }}</span>
                    <span class="inline-block flex-shrink-0 badge-status" data-value="{{ $item ['status'] }}">{{ $item ['status'] }}</span>
                </div>
                <p class="mt-1 truncate text-sm text-gray-700">{{ $item ['badge'] }}</p>
                <p class="mt-1 truncate text-sm text-gray-500">{{ $item ['contact'] }}</p>
                <p class="mt-1 truncate text-sm text-gray-400">{{ $item ['email'] }}</p>
            </div>
            <form action="{{ route('users.destroy', $item->id) }}" method="POST">
                <div class="flex gap-2">
                    <a href="javascript:void(0)" data-open="modal" data-type="user" data-action="edit" data-id="{{ $item->id }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                        <iconify-icon icon="solar:eye-linear" class="text-md text-blue-500 p-1 mt-0.5"></iconify-icon>
                        <span class="sr-only">. Edit</span>
                    </a>
                    <a href="{{ route('users.destroy', $item->id) }}" class="text-red-600 hover:text-red-900" onclick="deleteItem(event, '{{ route('users.destroy', $item->id) }}')">
                        <iconify-icon icon="solar:trash-bin-minimalistic-outline" class="text-md text-black p-1"></iconify-icon>
                        <span class="sr-only">. Delete</span>        
                    </a>
                </div>
            </form>
        </div>
    </div>
    @empty
    @endforelse
    @if (session('modal'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = @json(session('modal'));

        if (modal.type === 'user' && modal.action === 'edit') {
            const form = document.querySelector('#userForm');
            const submitButton = document.getElementById('submitButton');
            const modalTitleUser = document.getElementById('modalTitleUser');
            const pass = document.getElementById('pass');


            form.action = `/users/${modal.id}`;

            let methodInput = form.querySelector('[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            modalTitleUser.innerText = 'Edit User';
            submitButton.innerText = 'Update';
            pass.classList.add('hidden');
        }
    });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function () {

    const inputSwitch2 = document.getElementById('inputSwitch2');
    const passwordField2 = document.getElementById('passwordField2');

    if (inputSwitch2 && passwordField2) {
        inputSwitch2.addEventListener('click', function () {
            passwordField2.classList.remove('hidden');
            inputSwitch2.classList.add('hidden');
        });
    }

    document.querySelectorAll('[data-reset="true"]').forEach((btn) => {
        btn.addEventListener('click', function () {

            const inputSwitch2 = document.getElementById('inputSwitch2');
            const passwordField2 = document.getElementById('passwordField2');

            if (inputSwitch2 && passwordField2) {
                passwordField2.classList.add('hidden');
                inputSwitch2.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('[data-open="modal"]').forEach(btn => {
        btn.addEventListener('click', function () {
            let action = this.dataset.action;
            let clickedId = this.dataset.id;
            let authId = "{{ auth()->id() }}";

            let passwordSection = document.getElementById('passwordSection');
            let passText = document.getElementById('pass');

            if (action === 'edit') {
                if (clickedId == authId) {
                    passwordSection.style.display = 'block';
                    passText.style.display = 'none';
                } else {
                    passwordSection.style.display = 'block';
                    passText.style.display = 'none';
                }
            }

            if (action === 'create') {
                passwordSection.style.display = 'none';
                passText.style.display = 'block';
            }
        });
    });
});

</script>
</x-layout>