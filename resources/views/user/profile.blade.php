<x-layout2>
    <div class="bg-black min-h-screen pb-5">
        <div class="pt-25 sm:pt-30 pb-2 rounded-4xl overflow-hidden">
            <div class="px-6 md:px-20 pb-5 relative">
                <x-bg class="relative h-60 w-full rounded-2xl ring-2 ring-gray-200 overflow-hidden"></x-bg>
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="flex flex-col absolute inset-0 items-center justify-center text-white z-10">
                    <h1 class=" text-4xl md:text-6xl font-semibold">
                        PROFILE USER
                    </h1>
                    <p class="text-xl md:text-2xl pt-2">Hai Hai Hai!!!</p>
                </div>
            </div>
        </div>
        <div class="px-6 md:px-21">
            <div class="mx-auto bg-gray-900 shadow-lg rounded-2xl p-8 ring-2 ring-gray-200">
                <form action="{{ route('profile.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <span class="badge-status" data-value="{{ $user ['status'] }}">{{ $user ['status'] }}</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 pt-3 sm:gap-6">
                        <div>
                            <div class="mb-4">
                                <label class="text-gray-200 font-semibold mb-2 block">Badge</label>
                                <input type="text" name="badge" value="{{ old('badge', $user->badge) }}"
                                    class="rounded-full text-gray-200 w-full p-3 border border-gray-300 focus:outline-none focus:border-[#2d7285]" readonly>
                                @error('badge')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="text-gray-200 font-semibold mb-2 block">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="rounded-full text-gray-200 w-full p-3 border border-gray-300 focus:outline-none focus:border-[#2d7285]">
                                @error('name')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <label class="text-gray-200 font-semibold mb-2 block">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="rounded-full text-gray-200 w-full p-3 border border-gray-300 focus:outline-none focus:border-[#2d7285]">
                                @error('email')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="text-gray-200 font-semibold mb-2 block">No Whatsapp</label>
                                <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                                    class="rounded-full text-gray-200 w-full p-3 border border-gray-300 focus:outline-none focus:border-[#2d7285]">
                                @error('contact')
                                    <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div>
                                <button type="button" id="inputSwitch" class="text-gray-200 sm:pt-10 cursor-pointer hover:text-[#2d7285] w-full sm:items-center">Ubah Password?</button>
                            </div>
                            <div id="passwordField" class="mb-6 hidden">
                                <label class="text-gray-200 font-semibold mb-2 block">New Password</label>
                                <input type="password" name="password"
                                    class="rounded-full text-gray-200 w-full p-3 border border-gray-300 focus:outline-none focus:border-[#2d7285]">
                            </div>
                            @error('password')
                                <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                    </div>
                    </div>

                    <div class="text-center pt-5">
                        <button type="submit"
                            class="bg-[#6dadbe] hover:bg-[#2d7285] text-black px-6 py-3 rounded-full transition cursor-pointer">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const inputSwitch = document.getElementById('inputSwitch');
        const passwordField = document.getElementById('passwordField');
        inputSwitch.addEventListener('click', function () {
            passwordField.classList.toggle('hidden');
            inputSwitch.classList.toggle('hidden');
        });
    </script>
</x-layout2>