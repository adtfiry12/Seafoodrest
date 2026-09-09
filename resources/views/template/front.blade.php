<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Niskala Samudra - Premium Seafood Dining</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { charcoal: { 800: '#27272a', 900: '#18181b', 950: '#09090b' }, gold: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' } },
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
<body class="bg-slate-50 text-slate-800 font-sans flex flex-col min-h-screen" x-data="cartSystem()">

    <div class="bg-charcoal-950 text-slate-300 text-xs py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex gap-6">
                <span class="flex items-center gap-2"><i class="fa-solid fa-phone text-gold-500"></i> +62 851-9119-4059</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-envelope text-gold-500"></i> niskalasamudra@gmail.com</span>
            </div>
            <div class="flex gap-4 items-center">
                <span class="flex items-center gap-2"><i class="fa-solid fa-clock text-gold-500"></i> Buka Setiap Hari: 10.00 - 22.00</span>
                <span class="h-4 w-px bg-slate-600"></span>
                <div class="flex items-center gap-3">
                    <a href="#" aria-label="Instagram" class="hover:text-gold-400 transition"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook" class="hover:text-gold-400 transition"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" aria-label="WhatsApp" class="hover:text-gold-400 transition"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 shadow-sm border-b border-slate-200" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" :class="scrolled ? 'py-2' : 'py-3 lg:py-4'" style="transition: all 0.3s ease;">
            <div class="flex items-center justify-between">

                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-1">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo" class="h-9 sm:h-12 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-2xl font-bold tracking-wide text-charcoal-950 font-serif leading-none">Niskala<span class="text-gold-600">Samudra</span></span>
                        </div>
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8 font-medium items-center text-sm uppercase tracking-wider">
                    <a href="{{ route('home') }}" class="transition {{ request()->routeIs('home') ? 'text-gold-600 font-bold' : 'text-charcoal-800 hover:text-gold-600' }}">Beranda</a>
                    <a href="{{ route('tentang') }}" class="transition {{ request()->routeIs('tentang') ? 'text-gold-600 font-bold' : 'text-charcoal-800 hover:text-gold-600' }}">Tentang Kami</a>
                    <a href="{{ route('menu') }}" class="transition {{ request()->routeIs('menu') || request()->routeIs('menu.detail') ? 'text-gold-600 font-bold' : 'text-charcoal-800 hover:text-gold-600' }}">Menu</a>
                    <a href="{{ route('kontak') }}" class="transition {{ request()->routeIs('kontak') ? 'text-gold-600 font-bold' : 'text-charcoal-800 hover:text-gold-600' }}">Kontak</a>
                    @auth
                        <a href="{{ route('front.pesanan') }}" class="transition {{ request()->routeIs('front.pesanan') ? 'text-gold-600 font-bold' : 'text-charcoal-800 hover:text-gold-600' }}">PESANAN</a>
                    @else
                        <button type="button" @click="loginHistoryModalOpen = true" class="transition text-charcoal-800 hover:text-gold-600">PESANAN</button>
                    @endauth
                </nav>

                <div class="flex items-center gap-3 sm:gap-5">
                    <button @click.prevent="isCartOpen = true" aria-label="Buka keranjang" class="relative text-charcoal-800 hover:text-gold-600 transition focus:outline-none">
                        <i class="fa-solid fa-cart-shopping text-lg sm:text-xl"></i>
                        <span x-show="cartTotalItems() > 0" x-text="cartTotalItems()" x-cloak class="absolute -top-2 -right-2 bg-gold-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-bounce"></span>
                    </button>
                    <div class="h-6 w-px bg-slate-300 mx-1 hidden md:block"></div>
                    @guest
                        <a href="{{ route('login') }}" class="hidden md:flex text-charcoal-800 hover:text-gold-600 font-semibold text-sm transition uppercase tracking-wider items-center gap-2">
                            Login <i class="fa-solid fa-user-circle text-lg"></i>
                        </a>
                    @else
                        <div class="hidden md:block relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group">
                                <img src="{{ Auth::user()->foto ? asset('storage/'.Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=18181b&color=fbbf24' }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 group-hover:border-gold-500 transition">
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-3 w-56 bg-white rounded-md shadow-xl py-2 z-50 border border-slate-100 text-slate-800">
                                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <p class="text-sm font-bold text-charcoal-900 truncate">{{ Auth::user()->nama }}</p>
                                    <span class="inline-block px-2 py-0.5 text-[10px] font-bold text-white bg-charcoal-800 rounded mt-1 uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                </div>
                                @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                                    <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700"><i class="fa-solid fa-gauge-high text-gold-600 w-4"></i> Dashboard </a>
                                @endif
                                <a href="{{ route('profile.index') }}" class=" px-4 py-2.5 text-sm hover:bg-slate-50 flex items-center gap-3 text-slate-700"><i class="fa-solid fa-user text-gold-600 w-4"></i> Pengaturan Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 flex items-center gap-3 border-t border-slate-100 mt-1"><i class="fa-solid fa-right-from-bracket w-4"></i> Keluar</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-charcoal-800 hover:text-gold-600 focus:outline-none ml-2">
                        <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" x-collapse x-cloak class="md:hidden bg-white border-t border-slate-100 shadow-xl absolute w-full left-0 top-full">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-3 text-sm uppercase tracking-wider border-b border-slate-50 transition {{ request()->routeIs('home') ? 'text-gold-600 font-bold' : 'text-charcoal-800 font-medium' }}">Beranda</a>
                <a href="{{ route('tentang') }}" class="block px-3 py-3 text-sm uppercase tracking-wider border-b border-slate-50 transition {{ request()->routeIs('tentang') ? 'text-gold-600 font-bold' : 'text-charcoal-800 font-medium' }}">Tentang Kami</a>
                <a href="{{ route('menu') }}" class="block px-3 py-3 text-sm uppercase tracking-wider border-b border-slate-50 transition {{ request()->routeIs('menu') || request()->routeIs('menu.detail') ? 'text-gold-600 font-bold' : 'text-charcoal-800 font-medium' }}">Menu</a>
                <a href="{{ route('kontak') }}" class="block px-3 py-3 text-sm uppercase tracking-wider border-b border-slate-50 transition {{ request()->routeIs('kontak') ? 'text-gold-600 font-bold' : 'text-charcoal-800 font-medium' }}">Kontak</a>
                @auth
                    <a href="{{ route('front.pesanan') }}" class="block px-3 py-3 text-sm uppercase tracking-wider border-b border-slate-50 transition {{ request()->routeIs('front.pesanan') ? 'text-gold-600 font-bold' : 'text-charcoal-800 font-medium' }}">PESANAN</a>
                @else
                    <button type="button" @click="loginHistoryModalOpen = true; mobileMenuOpen = false" class="block w-full px-3 py-3 text-left text-sm uppercase tracking-wider border-b border-slate-50 transition text-charcoal-800 hover:text-gold-600">PESANAN</button>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="block px-3 py-3 mt-4 text-sm font-bold text-white bg-charcoal-900 text-center rounded hover:bg-gold-600 uppercase tracking-wider transition">Login / Masuk</a>
                @else
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Akun Saya</p>
                        @if(in_array(Auth::user()->role, ['admin', 'karyawan']))
                            <a href="{{ Auth::user()->role == 'admin' ? route('admin.dashboard') : route('dashboard.karyawan') }}" class="block px-3 py-3 text-sm text-charcoal-800 font-medium border-b border-slate-50">Dashboard</a>
                        @endif
                        <a href="{{ route('profile.index') }}" class="block px-3 py-3 text-sm text-charcoal-800 font-medium border-b border-slate-50">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-3 text-sm text-red-600 font-medium">Keluar</button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>

    </header>

    <div x-show="loginHistoryModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="login-history-modal-title">
        <div x-show="loginHistoryModalOpen" x-transition.opacity @click="loginHistoryModalOpen = false" class="absolute inset-0 bg-charcoal-950/70 backdrop-blur-sm"></div>

        <div x-show="loginHistoryModalOpen" x-transition class="relative my-auto w-full max-w-sm overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl">
            <div class="h-1.5 bg-gold-500"></div>
            <div class="px-6 pb-6 pt-8 text-center sm:px-8">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-gold-200 bg-gold-50 text-gold-600 shadow-sm">
                    <i class="fa-solid fa-receipt text-2xl"></i>
                </div>
                <h2 id="login-history-modal-title" class="font-serif text-2xl font-bold text-charcoal-900">Riwayat Pesanan</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-500">Login untuk melihat status dan riwayat pesanan Anda dengan mudah.</p>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:flex-row sm:px-8">
                <button type="button" @click="loginHistoryModalOpen = false" class="flex-1 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">Kembali</button>
                <a href="{{ route('login') }}" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-charcoal-900 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-gold-600">
                    Login Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grow">
        @yield('content')
    </div>

    <footer class="bg-charcoal-950 text-slate-300 pt-16 pb-8 border-t-4 border-gold-600 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12 mb-12">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center gap-1 mb-6">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo" class="h-14 w-auto object-contain filter brightness-110">
                        <div class="flex flex-col justify-center">
                            <span class="text-2xl font-bold tracking-wide text-white font-serif leading-none">Niskala<span class="text-gold-500">Samudra</span></span>
                        </div>
                    </a>
                    <p class="text-sm text-slate-400 leading-relaxed font-light mb-6">Menyajikan olahan hasil laut premium dengan bumbu rempah otentik pilihan. Nikmati sensasi bersantap elegan yang tak terlupakan bersama kami.</p>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-6 text-lg border-b-2 border-charcoal-800 pb-2 inline-block">Eksplorasi</h4>
                    <ul class="space-y-3 text-sm font-light">
                        <li><a href="{{ route('home') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs text-gold-600"></i> Beranda</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs text-gold-600"></i> Menu Restoran</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs text-gold-600"></i> Tentang Kami</a></li>
                        <li><a href="{{ route('kontak') }}" class="hover:text-gold-400 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-xs text-gold-600"></i> Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-6 text-lg border-b-2 border-charcoal-800 pb-2 inline-block">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm font-light text-slate-400">
                        <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-gold-500"></i> Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-gold-500"></i> +62 851-9119-4059</li>
                        <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-gold-500"></i> niskalasamudra@gmail.com</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-serif font-bold mb-6 text-lg border-b-2 border-charcoal-800 pb-2 inline-block">Jam Operasional</h4>
                    <ul class="space-y-3 text-sm font-light text-slate-400 bg-charcoal-900 p-5 rounded-xl border border-charcoal-800">
                        <li class="flex justify-between border-b border-charcoal-800 pb-2"><span>Senin - Jumat</span> <span class="text-white font-bold">10:00 - 22:00</span></li>
                        <li class="flex justify-between pt-1"><span>Sabtu - Minggu</span> <span class="text-white font-bold">09:00 - 23:00</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-charcoal-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-light text-slate-500 text-center md:text-left">
                <p>&copy; {{ date('Y') }} Niskala Samudra. Seluruh Hak Cipta Dilindungi.</p>
                <p>Didesain secara eksklusif oleh <span class="text-gold-500 font-bold">Aditya Mukti Firmansyah</span> (SMK Wikrama 1 Jepara).</p>
                <div class="flex space-x-5">
                    <a href="#" class="hover:text-gold-400 transition text-lg"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-gold-400 transition text-lg"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#" class="hover:text-gold-400 transition text-lg"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <div x-show="isCartOpen" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-cloak>
        <div x-show="isCartOpen" x-transition.opacity class="fixed inset-0 bg-charcoal-900/75 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="isCartOpen" x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-md" @click.away="isCartOpen = false">
                        <div class="flex h-full flex-col bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-bold text-charcoal-900 font-serif" id="slide-over-title">Keranjang Pesanan</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" @click="isCartOpen = false" class="relative -m-2 p-2 text-slate-400 hover:text-red-500 outline-none transition">
                                            <i class="fa-solid fa-xmark text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-8">
                                    <div class="flow-root">
                                        <div x-show="cart.length === 0" class="text-center py-10">
                                            <i class="fa-solid fa-basket-shopping text-4xl text-slate-200 mb-4"></i>
                                            <p class="text-slate-500 text-sm">Keranjang Anda masih kosong.</p>
                                            <button @click="isCartOpen = false; window.location.href='{{ route('menu') }}'" class="mt-4 px-6 py-2 border-2 border-gold-600 text-gold-600 font-bold rounded-lg hover:bg-gold-600 hover:text-white transition text-sm">Lihat Menu</button>
                                        </div>
                                        <ul role="list" class="-my-6 divide-y divide-slate-100">
                                            <template x-for="(item, index) in cart" :key="item.id">
                                                <li class="flex py-6">
                                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg border border-slate-200 shadow-sm">
                                                        <img :src="item.image" :alt="item.nama" class="h-full w-full object-cover object-center">
                                                    </div>
                                                    <div class="ml-4 flex flex-1 flex-col">
                                                        <div>
                                                            <div class="flex justify-between text-sm font-bold text-charcoal-900">
                                                                <h3 x-text="item.nama" class="truncate pr-2"></h3>
                                                                <p class="ml-2 text-gold-600 whitespace-nowrap" x-text="formatRupiah(item.harga * item.qty)"></p>
                                                            </div>
                                                            <p class="mt-1 text-xs text-slate-500" x-text="'Rp ' + formatRupiah(item.harga) + ' / porsi'"></p>
                                                        </div>
                                                        <div class="flex flex-1 items-end justify-between text-sm">
                                                            <div class="flex items-center border border-slate-200 rounded-lg">
                                                                <button @click="kurangiQty(index)" class="px-2 py-0.5 text-slate-500 hover:text-gold-600"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                                                <span class="px-2 font-bold text-charcoal-900" x-text="item.qty"></span>
                                                                <button @click="tambahQty(index)" class="px-2 py-0.5 text-slate-500 hover:text-gold-600"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                                            </div>
                                                            <button @click="hapusItem(index)" type="button" class="font-bold text-red-400 hover:text-red-600 text-xs uppercase tracking-wider transition">Hapus</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 px-4 py-6 sm:px-6 bg-slate-50" x-show="cart.length > 0">
                                <div class="flex justify-between text-base font-bold text-charcoal-900 mb-4">
                                    <p>Total Pembayaran</p>
                                    <p x-text="'Rp ' + formatRupiah(cartTotalPrice())"></p>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('checkout.index') }}" class="flex items-center justify-center rounded-xl border border-transparent bg-charcoal-900 px-6 py-4 text-sm font-bold text-white uppercase tracking-wider shadow-md hover:bg-gold-600 transition-colors active:scale-95">
                                        Lanjut Checkout <i class="fa-solid fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
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
                    let index = this.cart.findIndex(item => item.id === id);
                    if (index !== -1) {
                        if(this.cart[index].qty < maxStok) { this.cart[index].qty++; this.isCartOpen = true; }
                        else { alert('Stok menu ini tidak mencukupi!'); }
                    } else {
                        this.cart.push({ id, nama, harga, image, qty: 1, maxStok }); this.isCartOpen = true;
                    }
                },
                tambahQty(index) { if(this.cart[index].qty < this.cart[index].maxStok) this.cart[index].qty++; },
                kurangiQty(index) { if (this.cart[index].qty > 1) this.cart[index].qty--; else this.hapusItem(index); },
                hapusItem(index) { this.cart.splice(index, 1); },
                cartTotalItems() { return this.cart.reduce((total, item) => total + item.qty, 0); },
                cartTotalPrice() { return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0); },
                formatRupiah(angka) { return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(angka); }
            }));
        });
    </script>
</body>
</html>
