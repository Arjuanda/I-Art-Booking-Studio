<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css'])
    <title>Ticket</title>
    <link rel="icon" sizes="16x16" href="/storage/images/logo.png">
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen px-8">
    <div class="bg-gradient-to-r from-teal-500 to-emerald-600 w-full max-w-[700px] rounded-2xl shadow-2xl flex flex-col sm:flex-row overflow-hidden">
        <div class="p-6 flex-1 text-white order-3 md:order-1">
            <h2 class="text-2xl font-bold">I-Art Studio</h2>
            <p class="text-sm text-white/80">Tiket Booking Studio</p>

            <div class="mt-4 space-y-2 text-white">
                <div class="flex">
                    <span class="w-17 font-semibold">Nama</span>
                    <span>: {{ $booking->bookMaker->name }}</span>
                </div>

                <div class="flex">
                    <span class="w-17 font-semibold">Tanggal</span>
                    <span>: {{ $booking->start->format('d-m-Y') }}</span>
                </div>

                <div class="flex">
                    <span class="w-17 font-semibold">Waktu</span>
                    <span>: {{ $booking->start->format('H:i') }} - {{ $booking->end->format('H:i') }}</span>
                </div>

                <div class="flex">
                    <span class="w-17 font-semibold">Durasi</span>
                    <span>
                        : <span class="w-17 font-semibold">
                            {{ $booking->start->locale('id')->diffForHumans($booking->end, true) }}
                        </span>
                    </span>
                </div>

                <div class="flex">
                    <span class="w-17 font-semibold">Status</span>
                    <span>
                        : <span class="bg-white text-emerald-600 px-2 py-1 rounded-full text-xs">
                            {{ $booking->status }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
        <div class="w-full h-px md:w-1 md:h-auto border-t-2 md:border-l-2 border-dashed border-white/50 relative order-2">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 md:top-1/2 md:left-0 md:-translate-x-1/2 md:-translate-y-1/2 w-6 h-6 bg-gray-900 rounded-full"></div>
        </div>
        <div class="w-full md:w-1/3 bg-gray-300 p-6 flex flex-col items-center justify-center order-1 md:order-3">
            <div class="w-32 h-32 flex items-center justify-center rounded-lg">
                <img src="/storage/images/logo.png" alt="logo.jpg">
            </div>
        </div>
    </div>
</body>
</html>