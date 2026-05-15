<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @vite(['resources/css/app.css'])
  <title>I-Art</title>
  <link rel="icon" sizes="16x16" href="/storage/images/logo.png">
  <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
</head>

<body class="h-full antialiased overflow-y-auto scroll-hide" data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">

<div class="min-h-screen text-white relative">
    <x-bg></x-bg>
    <div class="absolute inset-0 bg-black/70"></div>
    <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-b from-transparent to-black"></div>
    <div class="relative z-10">
        <nav id="navbar" class="flex sticky top-0 z-50 items-center justify-between px-10 md:px-20 py-6 transition-all duration-300">      
            <div class="flex gap-2">
                <img class="h-11 w-11" src="/storage/images/logo.png" alt="Logo">
                <div class="hidden lg:flex text-2xl font-semibold items-center signature">I-Art</div>
            </div>
    
            <ul class="hidden lg:flex gap-8 text-sm text-gray-300">
                <li><a href="#" class="hover:text-gray-100">Home</a></li>
                <li><a href="#instrument" class="hover:text-gray-100">Instrument</a></li>
                <li><a href="#about" class="hover:text-gray-100">About</a></li>
                <li><a href="#event" class="hover:text-gray-100">Event</a></li>
                <li><a href="#faq" class="hover:text-gray-100">FAQ</a></li>
                <li><a href="#contact" class="hover:text-gray-100">Contact Us</a></li>
            </ul>
    
            <div class="flex gap-4 items-center text-slate-600">
                <div class="relative order-2 lg:order-1">
                    <iconify-icon id="notifBtn" icon="clarity:notification-line" class="text-xl p-2 mt-1 text-white hover-gradient rounded-full cursor-pointer"></iconify-icon>
                    @if(($notifications ?? collect())->whereNull('read_at')->count())
                        <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1 rounded-full">
                            {{ ($notifications ?? collect())->whereNull('read_at')->count() }}
                        </span>
                    @endif
                </div>
                <x-notif class="order-3 lg:order-3"></x-notif>
                <a href="/profile" class="p-2 rounded-2xl hover:bg-gray-700">
                    @if (auth()->check())
                    <h6 class="text-bold text-white order-1 lg:order-2">Hi {{ explode(' ', auth()->user()->name)[0] }}!</h6>
                    @endif
                </a>
                <div class="hidden lg:flex rounded-full bg-gray-200 hover:bg-gray-300 text-black px-4 py-2 cursor-pointer order-4">
                    <a href="/logout">Logout</a>
                </div>
            </div>
            <button id="menuBtn" class="lg:hidden text-white text-2xl cursor-pointer">
                ☰
            </button>
        </nav>
    
        <div id="mobileMenu" class="fixed inset-0 bg-black/90 backdrop-blur-lg z-50 flex flex-col items-center justify-center space-y-6 text-white text-lg opacity-0 scale-95 pointer-events-none transition-all duration-300">
            <a href="#">Home</a>
            <a href="#instrument">Instrument</a>
                <a href="#about">About</a>
                <a href="#event">Event</a>
                <a href="#faq">FAQ</a>
                <a href="#contact">Contact Us</a>
        
                <a href="/logout" class="bg-gray-200 text-black px-4 py-2 rounded-lg">
                    Logout
                </a>
        
                <button id="closeMenu" class="absolute top-6 right-6 text-3xl cursor-pointer">✕</button>
        </div>
        <div class="flex flex-col items-center text-center mt-5 md:mt-20 px-6">
            <h1 class="text-4xl md:text-6xl font-semibold leading-tight max-w-4xl">
                Tempat Dimana <br> Hobi dan Kreatifitas Bersatu
            </h1>
            <p id="instrument" class="text-gray-400 mt-6 max-w-xl">
                Pastikan Hobi Bermusikmu Tersalurkan.
            </p>
            @if($user->status === 'active')
            <a href="/booking" class="md:mt-12 mt-5 bg-white text-black px-6 py-3 rounded-full font-medium cursor-pointer">
                Buat Jadwal
            </a>
            @elseif($user->status === 'banned')
            <h4 class="md:mt-12 mt-5 bg-red-500 text-black px-6 py-3 rounded-full font-medium">Akun Anda di Ban</h4>
            @else
            <h4 class="md:mt-12 mt-5 bg-yellow-500 text-black px-6 py-3 rounded-full font-medium">Akun Anda Belum Aktif</h4>
            @endif

        </div>
    </div>

    <div class="absolute -bottom-40 left-0 right-0 mx-auto w-[90%] md:w-[70%]">
        <div class="backdrop-blur-xl bg-white/10 border border-white/10 rounded-2xl p-6 shadow-2xl">
      
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold mx-auto">Alat Musik di I-Art</h2>
            </div>

            <div class="marquee-wrapper mb-4">
                <div class="marquee">
                    <div class="marquee-content gap-8">
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/blue.png" class="marquee-item h-40 rotate-45">
                            <span class="marquee-item">Fender Stratocaster</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/drum.png" class="marquee-item h-40">
                            <span class="marquee-item">Drum Ludwig</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/gold.png" class="marquee-item h-40">
                            <span class="marquee-item">Fender Squire Classic</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/psr150.png" class="marquee-item h-40">
                            <span class="marquee-item">Yamaha Psr 150</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/bass.png" class="marquee-item h-40 rotate-45">
                            <span class="marquee-item">Yamaha TRBX174</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/blue.png" class="marquee-item h-40 rotate-45">
                            <span class="marquee-item">Fender Stratocaster</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/drum.png" class="marquee-item h-40">
                            <span class="marquee-item">Drum Ludwig</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/gold.png" class="marquee-item h-40">
                            <span class="marquee-item">Fender Squire Classic</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/psr150.png" class="marquee-item h-40">
                            <span class="marquee-item">Yamaha Psr 150</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="/storage/images/bass.png" class="marquee-item h-40 rotate-45">
                            <span class="marquee-item">Yamaha TRBX174</span>
                        </div>
                    </div>
                </div>
                <h2 class=" hidden absolute text-4xl text-white right-111 font-bold -bottom-45 text-center">About Us</h2>
            </div>
        </div>
        <div id="about"></div>
    </div>
</div>
<div class="h-full bg-black">
    <div class="pt-60 sm:pt-70">
        <h2 class="text-4xl pb-15 sm:pb-0 font-semibold text-white text-center">About Us</h2>
        <div class="flex flex-col lg:flex-row px-10 sm:px-20 mx-auto gap-10 justify-between items-center bg-black">
            <div class="md:w-3/4 lg:w-1/2 w-full">
                <h2 class="text-white text-[80px] sm:text-[100px] font-bold font-hero leading-none">I-ART ITU APA SIH?</h2>
                <p class="text-gray-400 pr-20 md:pr-60">I-Art adalah organisasi yang bergerak dibidang seni, khususnya dibidang musik. Organisasi ini bertujuan untuk memberikan wadah 
                    untuk karyawan Infineon yang memiliki hobi bermusik.            
                </p>
            </div>
            @if($post && $post->count() > 0)
            <div class="p-0 sm:p-20 flex flex-col items-center">
                @if($post)
                    <div class="relative w-[320px] h-[450px] group">
                        @foreach($post->take(3) as $loopIndex => $item)
                            <div 
                                class="transition-all duration-300 
                                    group-hover:-translate-y-4
                                    md:absolute md:w-full md:h-full
                                    {{ $loopIndex > 0 ? 'hidden md:block' : '' }}"
                                style="
                                    top: {{ $loopIndex * 70 }}px;
                                    transform: 
                                        translateX({{ $loopIndex == 0 ? '0px' : ($loopIndex == 1 ? '-150px' : '80px') }})
                                        scale({{ 1 - ($loopIndex * 0.02) }});
                                    z-index: {{ 9 - $loopIndex }};
                                    opacity: {{ 1 - ($loopIndex * 0.15) }};
                                "
                            >
                                <div class="rounded-2xl shadow-xl overflow-hidden bg-gray-200">
                                    <x-post :item="$item" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p></p>
                @endif
                <div x-data="{
                        open: false,
                        index: 0,
                        pics: [],
                        prev() { this.index = (this.index === 0 ? this.pics.length - 1 : this.index - 1); },
                        next() { this.index = (this.index === this.pics.length - 1 ? 0 : this.index + 1); }
                    }"
                    x-on:open-lightbox.window="open = true; index = $event.detail.index; pics = $event.detail.pics"
                    x-show="open"
                    x-transition
                    class="fixed inset-0 bg-black/80 bg-opacity-70 flex items-center justify-center z-50">

                    <img :src="@js(asset('storage') . '/') + pics[index]" class="max-h-full max-w-full rounded-lg">
                    <button x-on:click="open = false" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
                    <button x-on:click="prev()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&larr;</button>
                    <button x-on:click="next()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&rarr;</button>
                </div>
                @if($user->status === 'active' || $user->status === 'pending')
                <a href="/documentation" class="md:mt-0 -mt-30 w-32 py-3 rounded-full bg-white text-black text-center cursor-pointer z-9">Lainnya</a>
                @else
                <p class="md:mt-0 -mt-30 w-50 py-3 px-5 rounded-full bg-red-500 text-black text-center cursor-pointer z-9">Akun Anda di Ban</p>
                @endif
                @endif
            </div>
        </div>
    </div>
    <div id="event"></div>
    <div class="bg-black">
        <div class="pt-25 mx-auto w-[90%] md:w-[88%]">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-4xl font-semibold text-white mx-auto">Event</h2>
            </div>

            <div class="relative mt-10">
                <button id="leftBtn"
                    class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-black/60 hover:bg-black text-white p-3 rounded-full">
                    ❮
                </button>

                <div id="slider"
                    class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory px-10 {{ count($event) <= 2 ? 'justify-center' : '' }}">

                    @forelse($event as $item)
                    <div class="min-w-[380px] md:max-w-[420px] md:min-w-[420px] bg-white/10 p-4 rounded-2xl text-center flex-shrink-0 snap-start hover:scale-105 transition">

                        <img src="{{ asset('storage/images/'.$item->poster) }}"
                            class="mx-auto rounded-2xl h-[350px] w-[320px] md:w-[384px] object-cover">

                        <h3 class="text-white text-xl mt-5 font-bold font-hero">
                            {{ $item->title }}
                        </h3>

                        <p class="text-gray-400 text-xs">
                            Created By: {{ $item->eventMaker->name }}
                        </p>

                        <p class="text-gray-400 text-xs">
                            {{ $item->date }}
                        </p>

                        <p class="text-white mt-2 mb-4">
                            {{ $item->description }}
                        </p>

                    </div>
                    @empty
                    <h3 class="text-center w-full text-gray-200">Belum Ada Event</h3>
                    @endforelse

                </div>
                <button id="rightBtn"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-black/60 hover:bg-black text-white p-3 rounded-full">
                    ❯
                </button>
            </div>
        </div>
    </div>
    <div id="faq" class="bg-black text-black py-30 px-8">
        <h2 class="text-4xl text-white font-bold mb-10 text-center">FAQ</h2>

        <div class="max-w-3xl mx-auto">
            
            <div class="">
                <button class="w-full text-left bg-white px-4 py-3 rounded-full flex justify-between items-center faq-btn">
                    <span>Apakah ada biaya untuk menggunakan studio?</span>
                    <span class="cursor-pointer">+</span>
                </button>
                <p class="mt-2 px-4 py-2 faq-answer text-gray-300">
                    Tidak. Semua fasilitas studio di I-Art dapat digunakan secara gratis.
                </p>
            </div>
            <div class="faq-item">
                <button class="w-full text-left bg-white px-4 py-3 rounded-full flex justify-between items-center faq-btn">
                    <span>Siapa saja yang dapat menggunakan fasilitas studio musik I-art?</span>
                    <span class="cursor-pointer">+</span>
                </button>
                <p class="mt-2 faq-answer px-4 py-2 text-gray-300">
                    Semua karyawan Infineon yang telah tergabung dalam serikat pekerja dapat menggunakan fasilitas studio.
                </p>
            </div>
            <div class="">
                <button class="w-full text-left bg-white px-4 py-3 rounded-full flex justify-between items-center faq-btn">
                    <span>Apakah boleh membawa orang dari luar perusahaan ke studio?</span>
                    <span class="cursor-pointer">+</span>
                </button>
                <p class="mt-2 px-4 py-2 faq-answer text-gray-300">
                    Tidak boleh, karena perusahaan memberikan fasilitas studio musik hanya untuk karyawan Infineon.
                </p>
            </div>
        </div>
    </div>

    <div id="contact" class="bg-black text-white py-24 px-6 lg:px-20">
        <h2 class="text-4xl font-bold text-center mb-12">Contact Us</h2>

        <div class="max-w-5xl mx-auto space-y-16">
            <div class="text-center space-y-10">
                <p class="text-gray-400 text-lg">
                    Punya pertanyaan atau ingin mengunjungi studio I-Art? 
                    Hubungi kami melalui kontak berikut.
                </p>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white/5 p-6 rounded-2xl hover:scale-105 transition hover:bg-gradient-to-r hover:from-pink-500 hover:via-red-500 hover:to-yellow-500">
                        <a href="https://www.instagram.com/i_art.studio_official?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">
                        <i class="fa-brands fa-instagram fa-3x"></i>
                        <p class="text-sm text-gray-400">Instagram</p>
                        <p class="font-semibold">i_art.studio_official</p>
                        </a>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl hover:scale-105 transition hover:bg-green-600">
                        <a href="https://wa.me/6281266638285" target="_blank">
                        <!--<a href="https://chat.whatsapp.com/XXXXXXXXXXXXXXX" target="_blank"></a>-->
                        <i class="fa-brands fa-whatsapp fa-3x"></i>
                        <p class="text-sm text-gray-400">WhatsApp</p>
                        <p class="font-semibold">Bobby Andre</p>
                        </a>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl hover:bg-[#3367D6] hover:scale-105 transition">
                        <a href="https://maps.app.goo.gl/QntmrDKnjUTGtG9S8" target="_blank">
                        <i class="fa-solid fa-location-dot fa-3x"></i>
                        <p class="text-sm text-gray-400">Lokasi</p>
                        <p class="font-semibold">I-Art Studio</p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-white/5 p-8 rounded-2xl backdrop-blur text-center max-w-md mx-auto hover:bg-white/10 transition">

                <img src="/storage/images/bob.jpg" 
                    class="w-28 h-28 mx-auto rounded-full object-cover mb-4 border-2 border-white/20">

                <h3 class="text-xl font-semibold">Bobby Andre</h3>
                <p class="text-gray-400 text-sm">Ketua Organisasi I-Art</p>

                <p class="text-gray-400 mt-4 text-sm">
                    Bertanggung jawab atas pengelolaan dan penggunaan fasilitas studio I-Art 
                    serta kegiatan kesenian dalam organisasi.
                </p>
            </div>
        </div>
    </div>

</div>
<script>
    document.getElementById('notifBtn').addEventListener('click', function () {

        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);

            // hapus badge kalau sukses
            const badge = document.querySelector('.notif-badge');
            if (badge) badge.remove();
        });

    });
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");
    const closeMenu = document.getElementById("closeMenu");
    
    menuBtn.addEventListener("click", () => {
        mobileMenu.classList.remove("opacity-0", "scale-95", "pointer-events-none");
        mobileMenu.classList.add("opacity-100", "scale-100");
    });
    
    closeMenu.addEventListener("click", () => {
        mobileMenu.classList.remove("opacity-100", "scale-100");
        mobileMenu.classList.add("opacity-0", "scale-95", "pointer-events-none");
    });

    const nav = document.getElementById("navbar");
    
    window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            nav.classList.add("nav-scrolled");
        } else {
            nav.classList.remove("nav-scrolled");
        }
    });
    
    const slider = document.getElementById("slider");
    const leftBtn = document.getElementById("leftBtn");
    const rightBtn = document.getElementById("rightBtn");
    
    const scrollAmount = 320;
    
    rightBtn.addEventListener("click", () => {
        slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    leftBtn.addEventListener("click", () => {
        slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.faq-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const answer = btn.nextElementSibling;

                if (answer.style.maxHeight) {
                    answer.style.maxHeight = null;
                    answer.classList.remove('open');
                } else {
                    answer.style.maxHeight = answer.scrollHeight + "px";
                    answer.classList.add('open');
                }

                btn.querySelector('span:last-child').textContent =
                    answer.classList.contains('open') ? '-' : '+';
            });
        });
    });
</script>
</body>
</html>


<style>
.marquee-wrapper {
    overflow: hidden;
    white-space: nowrap;
}

.marquee {
    display: inline-block;
    width: max-content;
}

.marquee-content {
    display: flex;
    animation: scroll-left 20s linear infinite;
}

.marquee-item {
    display: inline-flex;
    align-items: center;
    font-size: 20px;
    font-weight: bold;
    color: transparent;
    background: linear-gradient(90deg, #ffb81c, #ff7a00);
    -webkit-background-clip: text;
    margin-right: 30px;
}

.marquee-item img {
    height: 32px;
    margin-right: 30px;
}

@keyframes scroll-left {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>

