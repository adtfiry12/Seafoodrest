<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niskala Samudra - Premium Seafood Dining</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans flex flex-col min-h-screen" x-data="cartSystem()">
    {{-- Global Progress Bar for Seamless Page Transitions --}}
    <div id="pjax-progress" class="fixed top-0 left-0 right-0 h-[2px] z-[100] transition-all duration-300 pointer-events-none opacity-0" style="background: linear-gradient(90deg, #d97706, #f59e0b); width: 0%;"></div>

    <div class="bg-charcoal-950 text-slate-400 text-xs py-2 hidden md:block border-b border-charcoal-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[12px]">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2 text-slate-300 hover:text-white transition"><i class="fa-solid fa-phone text-gold-500/90 text-[11px]"></i> +62 851-9119-4059</span>
                <span class="flex items-center gap-2 text-slate-300 hover:text-white transition"><i class="fa-solid fa-envelope text-gold-500/90 text-[11px]"></i> niskalasamudra@gmail.com</span>
            </div>
            <div class="flex gap-5 items-center">
                <span class="flex items-center gap-2 text-slate-300"><i class="fa-solid fa-clock text-gold-500/90 text-[11px]"></i> Buka Setiap Hari: 10.00 - 22.00</span>
                <span class="h-3 w-px bg-charcoal-800"></span>
                <div class="flex items-center gap-3 text-slate-400">
                    <a href="#" aria-label="Instagram" class="hover:text-gold-400 transition"><i class="fa-brands fa-instagram text-xs"></i></a>
                    <a href="#" aria-label="Facebook" class="hover:text-gold-400 transition"><i class="fa-brands fa-facebook text-xs"></i></a>
                    <a href="https://wa.me/6285191194059" target="_blank" rel="noopener" aria-label="WhatsApp" class="hover:text-gold-400 transition"><i class="fa-brands fa-whatsapp text-xs"></i></a>
                </div>
            </div>
        </div>
    </div>

    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 shadow-[0_1px_3px_rgba(0,0,0,0.04)] border-b border-slate-200/80" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" :class="scrolled ? 'py-2.5' : 'py-3.5 sm:py-4'" style="transition: all 0.25s ease;">
            <div class="flex items-center justify-between">

                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo" class="h-9 sm:h-11 w-auto object-contain transition group-hover:opacity-90">
                        <div class="flex flex-col justify-center">
                            <span class="text-xl sm:text-2xl font-serif font-bold tracking-tight text-charcoal-950 leading-none">Niskala<span class="text-gold-600 font-semibold ml-0.5">Samudra</span></span>
                        </div>
                    </a>
                </div>

                <nav class="hidden md:flex space-x-6 lg:space-x-8 items-center text-xs lg:text-[13px] font-bold uppercase tracking-wider" id="desktop-nav">
                    <a href="{{ route('home') }}" class="nav-link py-1 transition-colors {{ request()->routeIs('home') ? 'text-gold-500' : 'text-slate-700 hover:text-gold-500' }}">Beranda</a>
                    <a href="{{ route('tentang') }}" class="nav-link py-1 transition-colors {{ request()->routeIs('tentang*') ? 'text-gold-500' : 'text-slate-700 hover:text-gold-500' }}">Tentang Kami</a>
                    <a href="{{ route('menu') }}" class="nav-link py-1 transition-colors {{ request()->routeIs('menu*') ? 'text-gold-500' : 'text-slate-700 hover:text-gold-500' }}">Menu</a>
                    <a href="{{ route('kontak') }}" class="nav-link py-1 transition-colors {{ request()->routeIs('kontak*') ? 'text-gold-500' : 'text-slate-700 hover:text-gold-500' }}">Kontak</a>
                    @auth
                        <a href="{{ route('front.pesanan') }}" class="nav-link py-1 transition-colors {{ request()->routeIs('front.pesanan*') ? 'text-gold-500' : 'text-slate-700 hover:text-gold-500' }}">Pesanan</a>
                    @else
                        <button type="button" @click="loginHistoryModalOpen = true" class="nav-link py-1 transition-colors text-slate-700 hover:text-gold-500 uppercase tracking-wider font-bold text-xs lg:text-[13px]">Pesanan</button>
                    @endauth
                </nav>

                <div class="flex items-center gap-3 sm:gap-5">
                    <button @click.prevent="isCartOpen = true" aria-label="Buka keranjang" class="relative p-2 rounded-xl text-slate-700 hover:text-gold-600 hover:bg-slate-100/80 transition focus:outline-none">
                        <i class="fa-solid fa-cart-shopping text-base sm:text-lg"></i>
                        <span x-show="cartTotalItems() > 0" x-text="cartTotalItems()" x-cloak class="absolute -top-1 -right-1 bg-gold-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full shadow-xs"></span>
                    </button>
                    <div class="h-5 w-px bg-slate-200 mx-0.5 hidden md:block"></div>
                    @guest
                        <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-1.5 py-1 text-xs font-semibold text-slate-700 hover:text-gold-500 transition-colors">
                            <i class="fa-regular fa-circle-user text-[15px]"></i>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('login') }}" aria-label="Masuk ke Akun" class="md:hidden p-2 text-slate-700 hover:text-gold-500 transition-colors focus:outline-none">
                            <i class="fa-regular fa-circle-user text-lg"></i>
                        </a>
                    @else
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                                <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=18181b&color=fbbf24' }}" class="w-8 h-8 rounded-full object-cover border border-slate-200 group-hover:border-gold-500 transition shadow-xs">
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2.5 w-56 bg-white rounded-xl shadow-xl py-1.5 z-50 border border-slate-100 text-slate-800">
                                <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50/70">
                                    <p class="text-sm font-semibold text-charcoal-900 truncate">{{ Auth::user()->nama }}</p>
                                    <span class="inline-block px-2 py-0.5 text-[9px] font-semibold text-slate-600 bg-slate-200/80 rounded mt-0.5 uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                </div>
                                @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class="px-4 py-2 text-sm hover:bg-slate-50 flex items-center gap-2.5 text-slate-700"><i class="fa-solid fa-gauge-high text-gold-600 text-xs w-4"></i> Dashboard </a>
                                @endif
                                <a href="{{ route('profile.index') }}" class="px-4 py-2 text-sm hover:bg-slate-50 flex items-center gap-2.5 text-slate-700"><i class="fa-solid fa-user text-gold-600 text-xs w-4"></i> Pengaturan Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 border-t border-slate-100 mt-1"><i class="fa-solid fa-right-from-bracket text-xs w-4"></i> Keluar</button>
                                </form>
                            </div>
                        </div>
                        <a href="{{ route('profile.index') }}" aria-label="Profil Akun" class="md:hidden flex items-center p-1 rounded-full text-slate-700 hover:text-gold-500 transition">
                            <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=18181b&color=fbbf24' }}" class="w-7 h-7 rounded-full object-cover">
                        </a>
                    @endguest
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-xl text-slate-700 hover:text-gold-600 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0 top-full max-h-[calc(100vh-4.5rem)] overflow-y-auto">
            <div class="px-4 pt-2 pb-6 space-y-1 font-bold uppercase tracking-wider text-xs sm:text-sm" id="mobile-nav">
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="mobile-nav-link block px-3 py-2.5 rounded-lg transition {{ request()->routeIs('home') ? 'text-gold-600 bg-gold-50' : 'text-slate-700 hover:bg-slate-50' }}">Beranda</a>
                <a href="{{ route('tentang') }}" @click="mobileMenuOpen = false" class="mobile-nav-link block px-3 py-2.5 rounded-lg transition {{ request()->routeIs('tentang*') ? 'text-gold-600 bg-gold-50' : 'text-slate-700 hover:bg-slate-50' }}">Tentang Kami</a>
                <a href="{{ route('menu') }}" @click="mobileMenuOpen = false" class="mobile-nav-link block px-3 py-2.5 rounded-lg transition {{ request()->routeIs('menu*') ? 'text-gold-600 bg-gold-50' : 'text-slate-700 hover:bg-slate-50' }}">Menu</a>
                <a href="{{ route('kontak') }}" @click="mobileMenuOpen = false" class="mobile-nav-link block px-3 py-2.5 rounded-lg transition {{ request()->routeIs('kontak*') ? 'text-gold-600 bg-gold-50' : 'text-slate-700 hover:bg-slate-50' }}">Kontak</a>
                @auth
                    <a href="{{ route('front.pesanan') }}" @click="mobileMenuOpen = false" class="mobile-nav-link block px-3 py-2.5 rounded-lg transition {{ request()->routeIs('front.pesanan*') ? 'text-gold-600 bg-gold-50' : 'text-slate-700 hover:bg-slate-50' }}">Pesanan</a>
                @else
                    <button type="button" @click="loginHistoryModalOpen = true; mobileMenuOpen = false" class="mobile-nav-link block w-full px-3 py-2.5 text-left rounded-lg transition text-slate-700 hover:text-gold-600 hover:bg-slate-50 uppercase font-bold tracking-wider">Pesanan</button>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 px-3 py-2.5 mt-3 text-xs sm:text-sm font-bold uppercase tracking-wider text-white bg-charcoal-900 text-center rounded-xl hover:bg-gold-600 transition shadow-xs">
                        <i class="fa-regular fa-circle-user text-base text-gold-400"></i>
                        <span>Masuk Akun</span>
                    </a>
                @else
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Akun Saya</p>
                        @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class="block px-3 py-2 text-sm text-slate-700 font-medium rounded-lg hover:bg-slate-50">Dashboard</a>
                        @endif
                        <a href="{{ route('profile.index') }}" class="block px-3 py-2 text-sm text-slate-700 font-medium rounded-lg hover:bg-slate-50">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 text-sm text-rose-600 font-medium rounded-lg hover:bg-rose-50">Keluar</button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>

    </header>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-emerald-600 text-white px-4 py-2.5 shadow-xs relative z-30">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white p-1"><i class="fa-solid fa-xmark text-xs"></i></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-rose-600 text-white px-4 py-2.5 shadow-xs relative z-30">
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white p-1"><i class="fa-solid fa-xmark text-xs"></i></button>
            </div>
        </div>
    @endif

    <div x-show="loginHistoryModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="login-history-modal-title">
        <div x-show="loginHistoryModalOpen" x-transition.opacity @click="loginHistoryModalOpen = false" class="absolute inset-0 bg-charcoal-950/60 backdrop-blur-xs"></div>

        <div x-show="loginHistoryModalOpen" x-transition class="relative my-auto w-full max-w-sm overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
            <div class="px-6 pb-6 pt-7 text-center sm:px-7">
                <div class="mx-auto mb-4 flex h-13 w-13 items-center justify-center rounded-full bg-gold-50 text-gold-600 border border-gold-200/60">
                    <i class="fa-solid fa-receipt text-xl"></i>
                </div>
                <h2 id="login-history-modal-title" class="font-serif text-xl font-bold text-charcoal-950">Riwayat Pesanan</h2>
                <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-500">Login untuk melihat status dan riwayat pesanan Anda dengan mudah.</p>
            </div>

            <div class="flex flex-col-reverse gap-2.5 border-t border-slate-100 bg-slate-50/80 px-6 py-4 sm:flex-row sm:px-7">
                <button type="button" @click="loginHistoryModalOpen = false" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-medium text-slate-600 transition hover:bg-slate-100">Kembali</button>
                <a href="{{ route('login') }}" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-charcoal-900 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs transition hover:bg-gold-600">
                    Login Sekarang <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grow" id="main-content" style="transition: opacity 150ms ease-in-out;">
        @yield('content')
    </div>

    <footer class="bg-charcoal-950 text-slate-300 pt-12 pb-8 border-t border-charcoal-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo" class="h-10 sm:h-12 w-auto object-contain filter brightness-110">
                        <div class="flex flex-col justify-center">
                            <span class="text-xl font-bold tracking-tight text-white font-serif leading-none">Niskala<span class="text-gold-500">Samudra</span></span>
                        </div>
                    </a>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed font-light mb-4">Menyajikan olahan hasil laut premium dengan bumbu rempah otentik pilihan. Nikmati sensasi bersantap elegan yang tak terlupakan bersama kami.</p>
                </div>
                <div>
                    <h4 class="text-white font-serif font-semibold mb-4 text-base tracking-wide">Eksplorasi</h4>
                    <ul class="space-y-2.5 text-xs sm:text-sm font-light text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-gold-600"></i> Beranda</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-gold-600"></i> Menu Restoran</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-gold-600"></i> Tentang Kami</a></li>
                        <li><a href="{{ route('kontak') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-gold-600"></i> Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-semibold mb-4 text-base tracking-wide">Hubungi Kami</h4>
                    <ul class="space-y-3 text-xs sm:text-sm font-light text-slate-400">
                        <li class="flex items-start gap-2.5"><i class="fa-solid fa-location-dot mt-0.5 text-gold-500 text-xs"></i> <span>Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah</span></li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-phone text-gold-500 text-xs"></i> <span>+62 851-9119-4059</span></li>
                        <li class="flex items-center gap-2.5"><i class="fa-solid fa-envelope text-gold-500 text-xs"></i> <span>niskalasamudra@gmail.com</span></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-semibold mb-4 text-base tracking-wide">Jam Operasional</h4>
                    <div class="space-y-2 text-xs sm:text-sm font-light text-slate-400 bg-charcoal-900/60 p-4 rounded-xl border border-charcoal-800/80">
                        <div class="flex justify-between border-b border-charcoal-800/80 pb-2"><span>Senin - Jumat</span> <span class="text-white font-medium">10:00 - 22:00</span></div>
                        <div class="flex justify-between pt-1"><span>Sabtu - Minggu</span> <span class="text-white font-medium">09:00 - 23:00</span></div>
                    </div>
                </div>
            </div>
            <div class="border-t border-charcoal-800/80 pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs font-light text-slate-500 text-center md:text-left">
                <p>&copy; {{ date('Y') }} Niskala Samudra. Seluruh Hak Cipta Dilindungi.</p>
                <p>Didesain secara eksklusif oleh <span class="text-gold-500 font-medium">Aditya Mukti Firmansyah</span> (SMK Wikrama 1 Jepara).</p>
                <div class="flex space-x-4 text-slate-400">
                    <a href="#" aria-label="Instagram" class="hover:text-gold-400 transition text-sm"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook" class="hover:text-gold-400 transition text-sm"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://wa.me/6285191194059" target="_blank" rel="noopener" aria-label="WhatsApp" class="hover:text-gold-400 transition text-sm"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <div x-show="isCartOpen" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-cloak>
        <div x-show="isCartOpen" x-transition.opacity class="fixed inset-0 bg-charcoal-900/75 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-4 sm:pl-10">
                    <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-md" @click.away="isCartOpen = false">
                        <div class="flex h-full flex-col bg-white shadow-2xl">
                            <div class="flex-1 overflow-y-auto px-5 py-6 sm:px-6">
                                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-receipt text-gold-600 text-base"></i>
                                        <h2 class="text-base font-bold text-charcoal-950 font-serif tracking-tight" id="slide-over-title">Keranjang Pesanan</h2>
                                    </div>
                                    <button type="button" @click="isCartOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-charcoal-900 hover:bg-slate-100 outline-none transition" aria-label="Tutup keranjang">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                </div>
                                <div class="mt-6">
                                    <div class="flow-root">
                                        <div x-show="cart.length === 0" class="text-center py-12">
                                            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                <i class="fa-solid fa-basket-shopping text-xl"></i>
                                            </div>
                                            <p class="text-slate-600 text-sm font-medium">Keranjang Anda masih kosong.</p>
                                            <p class="text-slate-400 text-xs mt-1">Pilih hidangan laut segar untuk memesan.</p>
                                            <button @click="isCartOpen = false; window.location.href='{{ route('menu') }}'" class="mt-5 px-5 py-2.5 bg-charcoal-900 text-white font-medium rounded-xl hover:bg-gold-600 transition text-xs shadow-xs">Lihat Menu</button>
                                        </div>
                                        <ul role="list" class="-my-5 divide-y divide-slate-100">
                                            <template x-for="(item, index) in cart" :key="item.id">
                                                <li class="flex py-4 gap-3.5">
                                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-slate-200/80 bg-slate-100 shadow-2xs">
                                                        <img :src="item.image" :alt="item.nama" class="h-full w-full object-cover object-center">
                                                    </div>
                                                    <div class="flex flex-1 flex-col justify-between">
                                                        <div>
                                                            <div class="flex justify-between items-start text-xs sm:text-sm font-semibold text-charcoal-900">
                                                                <h3 x-text="item.nama" class="line-clamp-1 pr-2"></h3>
                                                                <p class="text-gold-700 font-bold whitespace-nowrap" x-text="formatRupiah(item.harga * item.qty)"></p>
                                                            </div>
                                                            <p class="text-[11px] text-slate-500 mt-0.5" x-text="'Rp ' + formatRupiah(item.harga) + ' / porsi'"></p>
                                                        </div>
                                                        <div class="flex items-center justify-between pt-2">
                                                            <div class="flex items-center border border-slate-200 rounded-lg bg-slate-50/50">
                                                                <button @click="kurangiQty(index)" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-gold-700 transition" aria-label="Kurangi porsi"><i class="fa-solid fa-minus text-[9px]"></i></button>
                                                                <span class="w-6 text-center text-xs font-bold text-charcoal-900" x-text="item.qty"></span>
                                                                <button @click="tambahQty(index)" class="w-6 h-6 flex items-center justify-center text-slate-500 hover:text-gold-700 transition" aria-label="Tambah porsi"><i class="fa-solid fa-plus text-[9px]"></i></button>
                                                            </div>
                                                            <button @click="hapusItem(index)" type="button" class="text-[11px] font-medium text-slate-400 hover:text-rose-600 transition">Hapus</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 px-5 py-5 sm:px-6 bg-slate-50/70" x-show="cart.length > 0">
                                <div class="flex justify-between text-sm font-semibold text-charcoal-900 mb-4">
                                    <p class="text-slate-600 font-normal">Subtotal Estimasi</p>
                                    <p class="text-base text-gold-700 font-bold" x-text="'Rp ' + formatRupiah(cartTotalPrice())"></p>
                                </div>
                                <a href="{{ route('checkout.index') }}" class="flex items-center justify-center gap-2 rounded-xl bg-charcoal-950 px-5 py-3.5 text-xs font-semibold text-white tracking-wider uppercase shadow-xs hover:bg-gold-600 transition-colors">
                                    Lanjut Checkout <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cartSystem', () => ({
                isCartOpen: false,
                loginHistoryModalOpen: false,
                cart: JSON.parse(localStorage.getItem('niskala_cart') || '[]'),
                init() { this.$watch('cart', val => { localStorage.setItem('niskala_cart', JSON.stringify(val)); }); },
                addToCart(id, nama, harga, image, maxStok) {
                    if (maxStok <= 0) {
                        alert('Maaf, stok hidangan ini sedang habis.');
                        return;
                    }
                    let index = this.cart.findIndex(item => item.id === id);
                    if (index !== -1) {
                        if(this.cart[index].qty < maxStok) {
                            this.cart[index].qty++;
                            this.isCartOpen = true;
                        } else {
                            alert('Stok menu ini sudah mencapai batas maksimal yang tersedia (' + maxStok + ' porsi).');
                        }
                    } else {
                        this.cart.push({ id, nama, harga, image, qty: 1, maxStok });
                        this.isCartOpen = true;
                    }
                },
                tambahQty(index) {
                    if(this.cart[index].qty < this.cart[index].maxStok) {
                        this.cart[index].qty++;
                    } else {
                        alert('Stok menu ini sudah mencapai batas maksimal yang tersedia (' + this.cart[index].maxStok + ' porsi).');
                    }
                },
                kurangiQty(index) { if (this.cart[index].qty > 1) this.cart[index].qty--; else this.hapusItem(index); },
                hapusItem(index) { this.cart.splice(index, 1); },
                cartTotalItems() { return this.cart.reduce((total, item) => total + item.qty, 0); },
                cartTotalPrice() { return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0); },
                formatRupiah(angka) { return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(angka); }
            }));
        });
    </script>
    <script>
        // High-performance SPA Router & Progress Bar for Niskala Samudra Front-end
        (function() {
            const ProgressBar = {
                el: null,
                timer: null,
                getEl() {
                    if (!this.el) this.el = document.getElementById('pjax-progress');
                    return this.el;
                },
                start() {
                    const bar = this.getEl();
                    if (!bar) return;
                    clearInterval(this.timer);
                    bar.style.opacity = '1';
                    bar.style.width = '30%';
                    let cur = 30;
                    this.timer = setInterval(() => {
                        if (cur < 85) {
                            cur += Math.random() * 12;
                            bar.style.width = Math.min(cur, 85) + '%';
                        }
                    }, 120);
                },
                done() {
                    const bar = this.getEl();
                    if (!bar) return;
                    clearInterval(this.timer);
                    bar.style.width = '100%';
                    setTimeout(() => {
                        bar.style.opacity = '0';
                        setTimeout(() => {
                            bar.style.width = '0%';
                        }, 250);
                    }, 180);
                }
            };

            function updateNavActiveState(pathname) {
                const clean = (p) => (p || '/').replace(/\/$/, '') || '/';
                const currentPath = clean(pathname);

                // Desktop navigation links
                document.querySelectorAll('#desktop-nav .nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        const linkPath = clean(new URL(href, window.location.origin).pathname);
                        const isMatch = (linkPath === '/' && currentPath === '/') || 
                                        (linkPath !== '/' && (currentPath === linkPath || currentPath.startsWith(linkPath + '/')));

                        if (isMatch) {
                            link.classList.remove('text-slate-700', 'text-charcoal-800', 'font-medium', 'text-gold-700', 'hover:text-gold-500', 'hover:text-gold-600');
                            link.classList.add('text-gold-500', 'font-bold');
                        } else {
                            link.classList.remove('text-gold-400', 'text-gold-500', 'text-gold-600', 'text-gold-700', 'font-bold');
                            link.classList.add('text-slate-700', 'font-medium', 'hover:text-gold-500');
                        }
                    } catch (e) {}
                });

                // Mobile navigation links
                document.querySelectorAll('#mobile-nav .mobile-nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        const linkPath = clean(new URL(href, window.location.origin).pathname);
                        const isMatch = (linkPath === '/' && currentPath === '/') || 
                                        (linkPath !== '/' && (currentPath === linkPath || currentPath.startsWith(linkPath + '/')));

                        if (isMatch) {
                            link.classList.remove('text-slate-700', 'text-charcoal-800', 'font-medium', 'text-gold-700', 'hover:bg-slate-50');
                            link.classList.add('text-gold-600', 'font-bold', 'bg-gold-50');
                        } else {
                            link.classList.remove('text-gold-400', 'text-gold-500', 'text-gold-600', 'text-gold-700', 'font-bold', 'bg-gold-50', 'bg-gold-50/60');
                            link.classList.add('text-slate-700', 'font-medium', 'hover:bg-slate-50');
                        }
                    } catch (e) {}
                });
            }

            // --- Menu Catalog Filter AJAX (Seamless, Banner remains static) ---
            window.navigateMenu = async function(url, updateHistory = true) {
                const root = document.getElementById('menu-catalog-root');
                if (!root) {
                    window.pjaxNavigate(url, updateHistory);
                    return;
                }

                root.classList.add('opacity-40', 'pointer-events-none');
                ProgressBar.start();

                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('Response error');

                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newRoot = doc.getElementById('menu-catalog-root');

                    if (newRoot) {
                        root.innerHTML = newRoot.innerHTML;
                        if (window.Alpine) {
                            window.Alpine.initTree(root);
                        }
                        if (updateHistory) {
                            window.history.pushState({ isMenuFilter: true }, '', url);
                        }
                        const newTitle = doc.querySelector('title');
                        if (newTitle) document.title = newTitle.innerText;
                    } else {
                        window.location.href = url;
                    }
                } catch (e) {
                    window.location.href = url;
                } finally {
                    root.classList.remove('opacity-40', 'pointer-events-none');
                    ProgressBar.done();
                }
            };

            // --- Global PJAX Navigation for #main-content ---
            window.pjaxNavigate = async function(url, updateHistory = true) {
                const main = document.getElementById('main-content');
                if (!main) {
                    window.location.href = url;
                    return;
                }

                ProgressBar.start();
                main.style.opacity = '0.5';

                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('Failed to load page');

                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newMain = doc.getElementById('main-content');

                    if (newMain) {
                        main.innerHTML = newMain.innerHTML;
                        const newTitle = doc.querySelector('title');
                        if (newTitle) document.title = newTitle.innerText;

                        if (window.Alpine) {
                            window.Alpine.initTree(main);
                        }

                        if (updateHistory) {
                            window.history.pushState({ pjax: true }, '', url);
                        }

                        updateNavActiveState(new URL(url, window.location.origin).pathname);
                        window.scrollTo({ top: 0, behavior: 'instant' });
                    } else {
                        window.location.href = url;
                    }
                } catch (e) {
                    window.location.href = url;
                } finally {
                    main.style.opacity = '1';
                    ProgressBar.done();
                }
            };

            // Global Click Interceptor
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                // Ignore modified clicks
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                if (link.origin !== window.location.origin) return;

                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;

                const urlObj = new URL(link.href);
                const path = urlObj.pathname;

                // Exclude system/auth/checkout/struk routes
                const excluded = ['/admin', '/karyawan', '/login', '/logout', '/register', '/checkout', '/struk'];
                if (excluded.some(prefix => path.startsWith(prefix))) return;
                if (link.hasAttribute('data-no-pjax')) return;

                // Case 1: Filter / pagination on /menu while already on /menu
                const isCurrentlyMenu = window.location.pathname === '/menu';
                const isTargetMenuListing = path === '/menu';
                const isInsideMenuCatalog = !!e.target.closest('#menu-catalog-root');

                if (isCurrentlyMenu && isTargetMenuListing && isInsideMenuCatalog) {
                    if (!path.startsWith('/menu/')) {
                        e.preventDefault();
                        window.navigateMenu(link.href);
                        return;
                    }
                }

                // Case 2: Standard internal navigation between front pages
                e.preventDefault();
                window.pjaxNavigate(link.href);
            });

            // Global Form Submit Interceptor for Menu Search
            document.addEventListener('submit', (e) => {
                const form = e.target.closest('form');
                if (!form) return;

                const action = form.getAttribute('action') || window.location.href;
                try {
                    const actionUrl = new URL(action, window.location.origin);
                    if (actionUrl.pathname === '/menu' && form.method.toUpperCase() === 'GET' && document.getElementById('menu-catalog-root')) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const params = new URLSearchParams();
                        for (const [key, value] of formData.entries()) {
                            if (value.trim() !== '') params.append(key, value.trim());
                        }
                        const query = params.toString();
                        const targetUrl = actionUrl.origin + actionUrl.pathname + (query ? '?' + query : '');
                        window.navigateMenu(targetUrl);
                    }
                } catch(err) {}
            });

            // Browser Back/Forward Navigation
            window.addEventListener('popstate', (e) => {
                const isMenuPage = window.location.pathname === '/menu';
                if (isMenuPage && document.getElementById('menu-catalog-root')) {
                    window.navigateMenu(window.location.href, false);
                } else {
                    window.pjaxNavigate(window.location.href, false);
                }
            });

            // Initial sync on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => updateNavActiveState(window.location.pathname));
            } else {
                updateNavActiveState(window.location.pathname);
            }
        })();
    </script>
</body>
</html>
