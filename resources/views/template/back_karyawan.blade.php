<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Niskala Samudra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { charcoal: { 800: '#27272a', 900: '#18181b', 950: '#09090b' }, gold: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' } }, fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap');
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-lg sm:hidden hover:bg-charcoal-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <!-- Logo ngarah ke dashboard.karyawan -->
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center gap-1 ml-2 md:mr-24">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-9 sm:h-12 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-2xl font-bold tracking-wide text-white font-serif leading-none">Niskala<span class="text-gold-500">Samudra</span></span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="relative ml-3" x-data="{ userMenu: false }">
                        <div>
                            <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex text-sm bg-charcoal-800 rounded-full focus:ring-4 focus:ring-charcoal-700">
                                <img class="w-8 h-8 rounded-full border border-gold-500/50 object-cover"
                                     src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Karyawan').'&background=f59e0b&color=fff' }}" alt="Karyawan Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-4 text-base bg-white divide-y divide-slate-100 rounded shadow border border-slate-200 w-48">
                            <div class="px-4 py-3">
                                <p class="text-sm text-charcoal-900 font-bold truncate">{{ Auth::user()->nama ?? 'Karyawan Resto' }}</p>
                                <p class="text-sm font-medium text-slate-500 truncate">{{ Auth::user()->email ?? 'karyawan@niskalasamudra.com' }}</p>
                            </div>
                            <ul class="py-1">
                                <li><a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-gold-600">Profil Saya</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-slate-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-2 font-medium text-sm">
                <li>
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('dashboard.karyawan') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-house w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard.karyawan') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Ringkasan Shift</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Tugas Operasional</p>
                    <a href="#" class="flex items-center p-3 text-charcoal-900 rounded-lg hover:bg-slate-100 group transition">
                        <i class="fa-solid fa-bell-concierge w-5 h-5 text-slate-400 group-hover:text-gold-600 transition"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Kelola Pesanan</span>
                        <span class="inline-flex items-center justify-center w-4 h-4 p-3 ml-3 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">2</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-charcoal-900 rounded-lg hover:bg-slate-100 group transition">
                        <i class="fa-solid fa-motorcycle w-5 h-5 text-slate-400 group-hover:text-gold-600 transition"></i>
                        <span class="ml-3">Pengantaran (Delivery)</span>
                    </a>
                    <a href="#" class="flex items-center p-3 text-charcoal-900 rounded-lg hover:bg-slate-100 group transition">
                        <i class="fa-solid fa-boxes-packing w-5 h-5 text-slate-400 group-hover:text-gold-600 transition"></i>
                        <span class="ml-3">Input Stok Masuk</span>
                    </a>
                </li>

                @if(Auth::user() && Auth::user()->role == 'admin')
                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Admin Mode</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-white rounded-lg bg-charcoal-900 hover:bg-charcoal-800 shadow-md group transition">
                        <i class="fa-solid fa-arrow-left-long w-5 h-5 text-gold-500 transition duration-75 group-hover:-translate-x-1"></i>
                        <span class="ml-3">Kembali ke Admin</span>
                    </a>
                </li>
                @endif

            </ul>

            <div class="mt-8 mb-4">
                <a href="{{ route('home') }}" class="flex items-center p-3 text-charcoal-900 rounded-lg bg-gold-50 border border-gold-200 hover:bg-gold-100 group transition shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 h-5 text-gold-600 transition"></i>
                    <span class="ml-3 font-semibold text-gold-700">Lihat Website</span>
                </a>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-charcoal-900/50 sm:hidden"></div>

    <div class="p-4 sm:ml-64 pt-24">
        @yield('content')
    </div>
</body>
</html>
