<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
    <link rel="icon" sizes="16x16" href="/storage/images/logo.png">
</head>
<body>
    <div class="flex justify-center h-full w-full min-h-screen pt-13 md:pt-28 relative">
        <x-bg class="absolute inset-0"></x-bg>
        <div class="absolute inset-0 bg-black/50 z-10"></div>
        <div class="relative z-20 w-full px-10">
            <div class="flex justify-center items-center">
                <div class="flex flex-col items-stretch justify-center gap-12 w-full">
                    <div class="w-full max-w-lg mx-auto bg-white/5 backdrop-blur-lg rounded-4xl p-8 shadow-[0_0_40px_rgba(255,255,255,0.35)]">
                        <div class="flex justify-center items-center h-30 w-30 mx-auto">
                            <img src="/storage/images/logo.png" alt="logo">
                        </div>
                        @if (session('failed'))
                            <div class="alert alert-danger text-red-500">{{ session('failed') }}</div>
                        @endif
                        <form action="/" method="POST">
                            @csrf
                            <div class="flex flex-col gap-5 mt-7">
                                <div>
                                    <label for="" class="text-gray-200 font-semibold mb-2 block">Badge</label>
                                    <input type="text" name="badge" value="{{ old('badge') }}" inputmode="numeric" class="text-gray-200 rounded-full w-full p-3 border border-gray-600 focus:outline-none focus:border-blue-400">
                                        @error('badge')
                                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="" class="text-gray-200 font-semibold mb-2 block">Password</label>
                                    <input type="password" name="password" class="text-gray-200 rounded-full w-full p-3 border border-gray-600 focus:outline-none focus:border-blue-400">
                                    @error('password')
                                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                </div>
                                <button type="submit" class="w-full text-center p-3 rounded-full bg-[#6dadbe] text-black hover:bg-[#2d7285]">
                                    Login
                                </button>
                                <div class="mt-2.5">
                                    <span class="text-base text-gray-200 font-medium">
                                        "Kamu anak baru? "
                                        <a href="/register" class="text-[#2d7285] font-medium ms-2">Sini buat akun</a>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>