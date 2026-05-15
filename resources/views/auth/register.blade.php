<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Register</title>
    <link rel="icon" sizes="16x16" href="/storage/images/logo.png">
</head>
<body>
    <div class="flex justify-center h-full w-full min-h-screen pt-13 md:pt-28 relative">
        <x-bg class="absolute inset-0"></x-bg>
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        <div class="relative z-20 w-full px-10">
            <div class="flex justify-center items-center">
                <div class="flex flex-col items-stretch justify-center gap-12 w-full">
                    <div class="w-full max-w-lg mx-auto bg-white/5 backdrop-blur-lg rounded-4xl p-8 shadow-[0_0_40px_rgba(255,255,255,0.35)] mb-20">
                        <div class="flex justify-center items-center h-30 w-30 mx-auto">
                            <img src="/storage/images/logo.png" alt="logo">
                        </div>
                        @if (session('failed'))
                            <div class="alert alert-danger text-red-500">{{ session('failed') }}</div>
                        @endif
                        <form action="/register" method="POST">
                            @csrf
                            <div class="flex flex-col gap-5 mt-7">
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">Badge</label>
                                        <input type="text" name="badge" placeholder="Harus 8 angka ya" value="{{ old('badge')}}" inputmode="numeric" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('badge')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">Nama</label>
                                        <input type="text" placeholder="Minimal 4 huruf ya" name="name" value="{{ old('name') }}" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('name')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">Email</label>
                                        <input type="email" placeholder="xxx@gmail.com" name="email" value="{{ old('email') }}" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('email')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">No Whatsapp</label>
                                        <input type="text" placeholder="08xxxxxx" name="contact" value="{{ old('contact') }}" inputmode="numeric" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('contact')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">Password</label>
                                        <input type="password" placeholder="Minimal 8 ya" name="password" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('password')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="" class="text-gray-200 font-semibold mb-2 block">Konfirmasi Password</label>
                                        <input type="password" placeholder="Minimal 8 ya" name="confirm_password" class="text-gray-200 rounded-full w-full p-3 border border-gray-200 focus:outline-none focus:border-blue-400">
                                        @error('confirm_password')
                                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                <button type="submit" class="w-full text-center p-3 rounded-full bg-[#6dadbe] text-black hover:bg-[#2d7285]">
                                    Register
                                </button>
                                <div class="mt-2.5">
                                    <span class="text-base text-gray-200 font-medium">
                                        "Sudah punya akun? "
                                        <a href="/" class="text-[#6dadbe] font-medium ms-2">Login</a>
                                    </span>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>