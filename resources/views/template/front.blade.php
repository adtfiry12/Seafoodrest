<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niskala Samudra - Premium Seafood Dining</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js plugin for collapse (optional but good for smooth mobile menu) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        charcoal: { 800: '#27272a', 900: '#18181b', 950: '#09090b' },
                        gold: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
                    },
                    fontFamily: {
                        serif: ['Playfair Display', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans flex flex-col min-h-screen">

    {{-- Top Bar  --}}
    <div class="bg-charcoal-950 text-slate-300 text-xs py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex gap-6">
                <span class="flex items-center gap-2"><i class="fa-solid fa-phone text-gold-500"></i> +62 812-3456-7890</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-envelope text-gold-500"></i> reservasi@niskalasamudra.com</span>
            </div>
            <div class="flex gap-4 items-center">
                <span class="flex items-center gap-2"><i class="fa-solid fa-clock text-gold-500"></i> Buka Setiap Hari: 10.00 - 22.00</span>
                <div class="border-l border-slate-600 pl-4 flex gap-3">
                    <a href="#" class="hover:text-gold-500 transition"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-gold-500 transition"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>

{{-- Navbar (Ditambah state mobileMenuOpen) --}}
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-slate-200"
            x-data="{ scrolled: false, mobileMenuOpen: false }"
            @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" :class="scrolled ? 'py-2' : 'py-3 lg:py-4'" style="transition: all 0.3s ease;">
            <div class="flex items-center justify-between">

                {{-- Logo dan nama  --}}
                <div class="shrink-0 flex items-center">
                    <a href="#" class="flex items-center gap-1">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-9 sm:h-12 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-2xl font-bold tracking-wide text-charcoal-950 font-serif leading-none">
                                Niskala<span class="text-gold-600">Samudra</span>
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Navbar Tengah (Desktop Only) --}}
                <nav class="hidden md:flex space-x-8 font-medium items-center text-sm uppercase tracking-wider text-charcoal-800">
                    <a href="#" class="text-gold-600 font-bold">Beranda</a>
                    <a href="#tentang" class="hover:text-gold-600 transition">Tentang Kami</a>

                    {{-- dropdown menu Desktop --}}
                    <div class="relative group" x-data="{ open: false }" @mouseleave="open = false">
                        <button @mouseover="open = true" class="flex items-center gap-1 hover:text-gold-600 transition py-2">
                            Menu <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute left-0 mt-0 w-48 bg-white rounded-md shadow-xl py-2 z-50 border border-slate-100">
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-gold-600">Ikan Laut</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-gold-600">Udang & Lobster</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-gold-600">Cumi & Gurita</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-gold-600">Minuman</a>
                        </div>
                    </div>

                    <a href="#kontak" class="hover:text-gold-600 transition">Kontak</a>

                    @auth
                        <a href="#" class="hover:text-gold-600 transition relative">
                            Pesanan
                            <span class="absolute -top-1 -right-2 flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span></span>
                        </a>
                    @endauth
                </nav>

                {{-- Icons, Auth & Hamburger Mobile --}}
                <div class="flex items-center gap-3 sm:gap-5">

                    {{-- search icon  --}}
                    <button class="text-charcoal-800 hover:text-gold-600 transition">
                        <i class="fa-solid fa-magnifying-glass text-base sm:text-lg"></i>
                    </button>

                    {{-- riwayat transaksi  --}}
                    <a href="#" class="relative text-charcoal-800 hover:text-gold-600 transition">
                        <i class="fa-solid fa-cart-shopping text-lg sm:text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-gold-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">3</span>
                    </a>

                    {{-- Garis ini cuma muncul di Desktop (md:block) --}}
                    <div class="h-6 w-px bg-slate-300 mx-1 hidden md:block"></div>

                    @guest
                        {{-- Tulisan Login ini disembunyikan di HP (hidden md:flex) --}}
                        <a href="{{ route('login') }}" class="hidden md:flex text-charcoal-800 hover:text-gold-600 font-semibold text-sm transition uppercase tracking-wider items-center gap-2">
                            Login <i class="fa-solid fa-user-circle text-lg"></i>
                        </a>
                    @else
                        <!-- Dropdown User disembunyikan di HP biar nggak nabrak -->
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                                <img src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=18181b&color=fbbf24' }}"
                                    alt="Profile" class="w-9 h-9 rounded-full object-cover border border-slate-200 group-hover:border-gold-500 transition">
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-md shadow-xl py-2 z-50 border border-slate-100 text-slate-800">
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-sm font-bold text-charcoal-900 truncate">{{ Auth::user()->nama }}</p>
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold text-white bg-charcoal-800 rounded mt-1 uppercase tracking-wider">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>

                                <!-- ================= LOGIKA ROUTE DASHBOARD (DESKTOP) ================= -->
                                @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                                    <a href="{{ Auth::user()->role == 'admin' ? route('dashboard.admin') : route('dashboard.karyawan') }}" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                                        <i class="fa-solid fa-gauge-high text-gold-600 w-4"></i> Dashboard
                                    </a>
                                @endif

                                <a href="#" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-user text-gold-600 w-4"></i> Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3 border-t border-slate-100 mt-1">
                                        <i class="fa-solid fa-right-from-bracket w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <!-- Hamburger Button (Mobile Only) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-charcoal-800 hover:text-gold-600 focus:outline-none ml-2">
                        <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="#" class="block px-3 py-3 text-sm font-bold text-gold-600 border-b border-slate-50 uppercase tracking-wider">Beranda</a>
                <a href="#tentang" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Tentang Kami</a>

                <!-- Kategori Mobile -->
                <div x-data="{ openKategori: false }" class="border-b border-slate-50">
                    <button @click="openKategori = !openKategori" class="w-full flex justify-between items-center px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 uppercase tracking-wider">
                        Menu <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="openKategori ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="openKategori" x-collapse class="bg-slate-50 pl-6 py-2 space-y-2 rounded-md">
                        <a href="#" class="block px-3 py-2 text-sm text-charcoal-700 hover:text-gold-600">Ikan Laut</a>
                        <a href="#" class="block px-3 py-2 text-sm text-charcoal-700 hover:text-gold-600">Udang & Lobster</a>
                        <a href="#" class="block px-3 py-2 text-sm text-charcoal-700 hover:text-gold-600">Cumi & Gurita</a>
                        <a href="#" class="block px-3 py-2 text-sm text-charcoal-700 hover:text-gold-600">Minuman</a>
                    </div>
                </div>

                <a href="#kontak" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Kontak</a>

                @auth
                    <!-- ================= LOGIKA ROUTE DASHBOARD (MOBILE) ================= -->
                    @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                        <a href="{{ Auth::user()->role == 'admin' ? route('dashboard.admin') : route('dashboard.karyawan') }}" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Dashboard</a>
                    @endif
                    <a href="#" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Profil / Pesanan Saya</a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-3 mt-4 text-sm font-bold text-white bg-charcoal-900 text-center rounded hover:bg-gold-600 uppercase tracking-wider transition">Login / Masuk</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- ================= SLIDER HERO SECTION ================= -->
    <section class="relative bg-charcoal-950 overflow-hidden group"
             x-data="{ activeSlide: 1, totalSlides: 3 }"
             x-init="setInterval(() => { activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1 }, 6000)">

        <div class="relative h-[450px] sm:h-[650px] w-full">

            <!-- Slide 1 -->
            <div x-show="activeSlide === 1" x-transition.opacity.duration.1000ms class="absolute inset-0">
                <img src="https://images.unsplash.com/photo-1559742811-822863646df8?q=80&w=1600" class="w-full h-full object-cover opacity-50">
                <div class="absolute inset-0 flex flex-col justify-center items-center px-4">
                    <div class="bg-charcoal-950/40 backdrop-blur-md border border-white/10 p-6 sm:p-12 rounded-2xl max-w-3xl text-center shadow-2xl transform transition-all">
                        <span class="text-gold-400 text-[10px] sm:text-sm font-bold tracking-[0.2em] uppercase mb-2 sm:mb-4 block">Kelezatan Premium</span>
                        <h1 class="text-3xl sm:text-6xl font-serif text-white mb-4 sm:mb-6 leading-tight">Sajian Laut Segar <br>Kualitas <span class="text-gold-500 font-italic">Terbaik</span></h1>
                        <p class="text-slate-200 text-xs sm:text-base mb-6 sm:mb-8 max-w-xl mx-auto font-light line-clamp-3 sm:line-clamp-none">Disiapkan langsung oleh koki profesional kami dengan bahan baku tangkapan nelayan lokal hari ini. Rasakan kemewahan di setiap gigitan.</p>
                        <a href="#menu-section" class="inline-block bg-gold-600 hover:bg-gold-500 text-white font-semibold py-2.5 sm:py-3.5 px-6 sm:px-8 rounded-sm text-xs sm:text-sm uppercase tracking-wider transition shadow-lg">
                            Eksplorasi Menu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div x-show="activeSlide === 2" x-transition.opacity.duration.1000ms class="absolute inset-0" x-cloak>
                <img src="https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?q=80&w=1600" class="w-full h-full object-cover opacity-50">
                <div class="absolute inset-0 flex flex-col justify-center items-center px-4">
                    <div class="bg-charcoal-950/40 backdrop-blur-md border border-white/10 p-6 sm:p-12 rounded-2xl max-w-3xl text-center shadow-2xl">
                        <span class="text-gold-400 text-[10px] sm:text-sm font-bold tracking-[0.2em] uppercase mb-2 sm:mb-4 block">Signature Dish</span>
                        <h1 class="text-3xl sm:text-6xl font-serif text-white mb-4 sm:mb-6 leading-tight">Kepiting Saus Padang <br>Spesial Koki</h1>
                        <p class="text-slate-200 text-xs sm:text-base mb-6 sm:mb-8 max-w-xl mx-auto font-light line-clamp-3 sm:line-clamp-none">Perpaduan daging kepiting jumbo segar dengan bumbu rempah otentik nusantara yang kaya rasa dan menggugah selera.</p>
                        <a href="#menu-section" class="inline-block border-2 border-gold-500 text-gold-400 hover:bg-gold-500 hover:text-white font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-sm text-xs sm:text-sm uppercase tracking-wider transition shadow-lg">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div x-show="activeSlide === 3" x-transition.opacity.duration.1000ms class="absolute inset-0" x-cloak>
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1600" class="w-full h-full object-cover opacity-50">
                <div class="absolute inset-0 flex flex-col justify-center items-center px-4">
                    <div class="bg-charcoal-950/40 backdrop-blur-md border border-white/10 p-6 sm:p-12 rounded-2xl max-w-3xl text-center shadow-2xl">
                        <span class="text-gold-400 text-[10px] sm:text-sm font-bold tracking-[0.2em] uppercase mb-2 sm:mb-4 block">Promo Khusus</span>
                        <h1 class="text-3xl sm:text-6xl font-serif text-white mb-4 sm:mb-6 leading-tight">Ikan Bakar <br>Khas Jimbaran</h1>
                        <p class="text-slate-200 text-xs sm:text-base mb-6 sm:mb-8 max-w-xl mx-auto font-light line-clamp-3 sm:line-clamp-none">Nikmati sensasi makan malam romantis dengan hidangan ikan bakar berkualitas yang diolah menggunakan tempurung kelapa asli.</p>
                        <a href="#menu-section" class="inline-block bg-white text-charcoal-900 hover:bg-slate-200 font-semibold py-2.5 sm:py-3.5 px-6 sm:px-8 rounded-sm text-xs sm:text-sm uppercase tracking-wider transition shadow-lg">
                            Lihat Promo
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tombol Navigasi Manual (Hidden on very small screens for cleaner UI) -->
        <button @click="activeSlide = activeSlide === 1 ? totalSlides : activeSlide - 1"
                class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white w-12 h-12 rounded-full items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-sm border border-white/20">
            <i class="fa-solid fa-chevron-left text-xl"></i>
        </button>
        <button @click="activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1"
                class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white w-12 h-12 rounded-full items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-sm border border-white/20">
            <i class="fa-solid fa-chevron-right text-xl"></i>
        </button>

        <!-- Indicator Bullets -->
        <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 flex justify-center space-x-2 sm:space-x-3">
            <template x-for="i in totalSlides">
                <button @click="activeSlide = i"
                        :class="activeSlide === i ? 'bg-gold-500 w-6 sm:w-8' : 'bg-white/50 w-2 sm:w-2.5'"
                        class="h-2 sm:h-2.5 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </section>

    <!-- ================= DAFTAR MENU ================= -->
    <main id="menu-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 flex-grow">

        <div class="text-center mb-8 sm:mb-12">
            <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Menu Favorit</span>
            <h2 class="text-2xl font-serif text-charcoal-900 sm:text-4xl">Sajian Pilihan Kami</h2>
            <div class="w-12 sm:w-16 h-1 bg-gold-500 mx-auto mt-3 sm:mt-4"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
            @php
                $sampleMenus = [
                    ['nama' => 'Kepiting Saus Padang', 'kategori' => 'Kepiting', 'harga' => 120000, 'img' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?q=80&w=400'],
                    ['nama' => 'Udang Bakar Madu', 'kategori' => 'Udang', 'harga' => 65000, 'img' => 'https://images.unsplash.com/photo-1559742811-822863646df8?q=80&w=400'],
                    ['nama' => 'Cumi Goreng Tepung', 'kategori' => 'Cumi', 'harga' => 45000, 'img' => 'https://images.unsplash.com/photo-1604909052743-94e838986d24?q=80&w=400'],
                    ['nama' => 'Ikan Gurame Bakar', 'kategori' => 'Ikan', 'harga' => 85000, 'img' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=400'],
                    ['nama' => 'Kerang Hijau Saus Tiram', 'kategori' => 'Kerang', 'harga' => 35000, 'img' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?q=80&w=400'],
                ];
            @endphp

            @foreach ($sampleMenus as $item)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col justify-between group">
                    <div>
                        <div class="relative overflow-hidden h-32 sm:h-48">
                            <img src="{{ $item['img'] }}" alt="{{ $item['nama'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[8px] sm:text-[10px] font-bold px-2 sm:px-3 py-1 uppercase tracking-wider">
                                {{ $item['kategori'] }}
                            </span>
                        </div>
                        <div class="p-3 sm:p-5 text-center">
                            <h3 class="font-serif font-bold text-charcoal-900 text-sm sm:text-lg leading-snug group-hover:text-gold-600 transition truncate sm:whitespace-normal">{{ $item['nama'] }}</h3>
                            <p class="text-gold-600 font-semibold text-xs sm:text-sm mt-1 sm:mt-2">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="p-3 sm:p-5 pt-0">
                        <button class="w-full bg-charcoal-900 hover:bg-gold-600 text-white font-semibold py-2 sm:py-2.5 transition duration-300 text-xs sm:text-sm flex items-center justify-center gap-1 sm:gap-2 tracking-wider">
                            <i class="fa-solid fa-cart-plus"></i> Pesan
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer id="kontak" class="bg-charcoal-950 text-slate-300 pt-12 sm:pt-16 pb-8 border-t-2 border-gold-600 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-12">

                <!-- Kolom 1 -->
                <div>
                    <a href="#" class="flex items-center gap-1 mb-4 sm:mb-6">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-10 sm:h-14 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-xl sm:text-2xl font-bold tracking-wide text-white font-serif leading-none">
                                Niskala<span class="text-gold-500">Samudra</span>
                            </span>
                        </div>
                    </a>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-light">Menyajikan olahan hasil laut premium dengan bumbu rempah pilihan. Nikmati sensasi bersantap elegan yang tak terlupakan bersama kami.</p>
                </div>

                <!-- Kolom 2 -->
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Eksplorasi</h4>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm font-light">
                        <li><a href="#" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Beranda</a></li>
                        <li><a href="#menu-section" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Menu Spesial</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Kolom 3 -->
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Hubungi Kami</h4>
                    <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm font-light text-slate-400">
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-gold-500"></i> Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-gold-500"></i> +62 812-3456-7890</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-gold-500"></i> reservasi@niskalasamudra.com</li>
                    </ul>
                </div>

                <!-- Kolom 4 -->
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Jam Buka</h4>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm font-light text-slate-400">
                        <li class="flex justify-between border-b border-charcoal-800 pb-2"><span>Senin - Jumat</span> <span class="text-white">10:00 - 22:00</span></li>
                        <li class="flex justify-between border-b border-charcoal-800 pb-2"><span>Sabtu - Minggu</span> <span class="text-white">09:00 - 23:00</span></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-charcoal-800 pt-6 sm:pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] sm:text-xs font-light text-slate-500 text-center md:text-left">
                <p>&copy; {{ date('Y') }} Niskala Samudra. Hak Cipta Dilindungi.</p>
                <p>
                    Dibuat oleh <span class="text-gold-500 font-semibold">Aditya Mukti Firmansyah</span> - SMK Wikrama 1 Jepara.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-gold-400 transition text-base sm:text-lg"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-gold-400 transition text-base sm:text-lg"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
