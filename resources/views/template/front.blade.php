<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niskala Samudra - Premium Seafood Dining</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] }
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

    <!-- Top Bar -->
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

    <!-- Navbar -->
    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-50 shadow-sm border-b border-slate-200"
            x-data="{ scrolled: false, mobileMenuOpen: false }"
            @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" :class="scrolled ? 'py-2' : 'py-3 lg:py-4'" style="transition: all 0.3s ease;">
            <div class="flex items-center justify-between">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-1">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-9 sm:h-12 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-2xl font-bold tracking-wide text-charcoal-950 font-serif leading-none">
                                Niskala<span class="text-gold-600">Samudra</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Navbar Tengah (Desktop) -->
                <nav class="hidden md:flex space-x-8 font-medium items-center text-sm uppercase tracking-wider text-charcoal-800">
                    <a href="{{ route('home') }}" class="hover:text-gold-600 transition {{ request()->routeIs('home') ? 'text-gold-600 font-bold' : '' }}">Beranda</a>
                    <a href="{{ route('home') }}#tentang" class="hover:text-gold-600 transition">Tentang Kami</a>
                    <a href="{{ route('home') }}#menu-section" class="hover:text-gold-600 transition">Menu</a>
                    <a href="#kontak" class="hover:text-gold-600 transition">Kontak</a>
                </nav>

                <!-- Icons & Auth -->
                <div class="flex items-center gap-3 sm:gap-5">

                    @auth
                        <!-- Keranjang Pesanan -->
                        <a href="#" class="relative text-charcoal-800 hover:text-gold-600 transition">
                            <i class="fa-solid fa-cart-shopping text-lg sm:text-xl"></i>
                            <span class="absolute -top-2 -right-2 bg-gold-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">0</span>
                        </a>
                    @endauth

                    <div class="h-6 w-px bg-slate-300 mx-1 hidden md:block"></div>

                    @guest
                        <a href="{{ route('login') }}" class="hidden md:flex text-charcoal-800 hover:text-gold-600 font-semibold text-sm transition uppercase tracking-wider items-center gap-2">
                            Login <i class="fa-solid fa-user-circle text-lg"></i>
                        </a>
                    @else
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                                <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=18181b&color=fbbf24' }}"
                                    alt="Profile" class="w-9 h-9 rounded-full object-cover border border-slate-200 group-hover:border-gold-500 transition">
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-md shadow-xl py-2 z-50 border border-slate-100 text-slate-800">
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-sm font-bold text-charcoal-900 truncate">{{ Auth::user()->nama }}</p>
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold text-white bg-charcoal-800 rounded mt-1 uppercase tracking-wider">
                                        {{ Auth::user()->role }}
                                    </span>
                                </div>

                                @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                                        <i class="fa-solid fa-gauge-high text-gold-600 w-4"></i> Dashboard Resto
                                    </a>
                                @endif

                                <!-- Link Profil Mengarah ke Profil Pelanggan -->
                                <a href="{{ route('profile.index') }}" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-user text-gold-600 w-4"></i> Pengaturan Profil
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

                    <!-- Hamburger Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-charcoal-800 hover:text-gold-600 focus:outline-none ml-2">
                        <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-3 text-sm font-bold text-gold-600 border-b border-slate-50 uppercase tracking-wider">Beranda</a>
                <a href="{{ route('home') }}#tentang" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Tentang Kami</a>
                <a href="{{ route('home') }}#menu-section" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Menu</a>
                <a href="#kontak" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Kontak</a>

                @auth
                    @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                        <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Dashboard</a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="block px-3 py-3 text-sm font-medium text-charcoal-800 hover:text-gold-600 border-b border-slate-50 uppercase tracking-wider">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}" class="block px-3 py-3">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-600 uppercase tracking-wider">Logout</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-3 mt-4 text-sm font-bold text-white bg-charcoal-900 text-center rounded hover:bg-gold-600 uppercase tracking-wider transition">Login / Masuk</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA DI-INJECT DI SINI -->
    <div class="flex-grow">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer id="kontak" class="bg-charcoal-950 text-slate-300 pt-12 sm:pt-16 pb-8 border-t-2 border-gold-600 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-12">
                <div>
                    <a href="#" class="flex items-center gap-1 mb-4 sm:mb-6">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo" class="h-10 sm:h-14 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-xl sm:text-2xl font-bold tracking-wide text-white font-serif leading-none">
                                Niskala<span class="text-gold-500">Samudra</span>
                            </span>
                        </div>
                    </a>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-light">Menyajikan olahan hasil laut premium dengan bumbu rempah pilihan. Nikmati sensasi bersantap elegan yang tak terlupakan bersama kami.</p>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Eksplorasi</h4>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm font-light">
                        <li><a href="#" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Beranda</a></li>
                        <li><a href="#menu-section" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Menu Spesial</a></li>
                        <li><a href="#tentang" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] sm:text-xs text-gold-600"></i> Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Hubungi Kami</h4>
                    <ul class="space-y-3 sm:space-y-4 text-xs sm:text-sm font-light text-slate-400">
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-gold-500"></i> Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-gold-500"></i> +62 812-3456-7890</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-gold-500"></i> reservasi@niskalasamudra.com</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-4 sm:mb-6 text-base sm:text-lg">Jam Buka</h4>
                    <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm font-light text-slate-400">
                        <li class="flex justify-between border-b border-charcoal-800 pb-2"><span>Senin - Jumat</span> <span class="text-white">10:00 - 22:00</span></li>
                        <li class="flex justify-between border-b border-charcoal-800 pb-2"><span>Sabtu - Minggu</span> <span class="text-white">09:00 - 23:00</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-charcoal-800 pt-6 sm:pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] sm:text-xs font-light text-slate-500 text-center md:text-left">
                <p>&copy; {{ date('Y') }} Niskala Samudra. Hak Cipta Dilindungi.</p>
                <p>Dibuat oleh <span class="text-gold-500 font-semibold">Aditya Mukti Firmansyah</span> - SMK Wikrama 1 Jepara.</p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-gold-400 transition text-base sm:text-lg"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-gold-400 transition text-base sm:text-lg"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
