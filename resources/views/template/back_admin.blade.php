<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Niskala Samudra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false, deleteModalOpen: false, deleteForm: null, deleteMessage: '', actionModalOpen: false, actionForm: null, actionTitle: '', actionMessage: '', openDeleteModal(event, message) { this.deleteForm = event.currentTarget; this.deleteMessage = message; this.deleteModalOpen = true; }, openActionModal(event, title, message) { this.actionForm = event.currentTarget; this.actionTitle = title; this.actionMessage = message; this.actionModalOpen = true; } }">

    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-lg sm:hidden hover:bg-charcoal-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 ml-2 md:mr-24">
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
                                     src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Admin').'&background=f59e0b&color=fff' }}" alt="Admin Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-4 text-base bg-white divide-y divide-slate-100 rounded shadow border border-slate-200 w-48">
                            <div class="px-4 py-3">
                                <p class="text-sm text-charcoal-900 font-bold truncate">{{ Auth::user()->nama ?? 'Administrator' }}</p>
                                <p class="text-sm font-medium text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@niskalasamudra.com' }}</p>
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
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.dashboard') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-house w-5 h-5 transition duration-75 {{ request()->routeIs('admin.dashboard') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Master Data</p>
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.kategori.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-tags w-5 h-5 transition {{ request()->routeIs('admin.kategori.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Kategori</span>
                    </a>
                    <a href="{{ route('admin.menu.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.menu.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-utensils w-5 h-5 transition {{ request()->routeIs('admin.menu.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Menu Seafood</span>
                    </a>
                    <a href="{{ route('admin.slide.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.slide.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-image w-5 h-5 transition {{ request()->routeIs('admin.slide.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Slide Banner</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-users w-5 h-5 transition {{ request()->routeIs('admin.pengguna.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Pengguna</span>
                    </a>
                </li>

                <!-- LOGIKA NOTIFIKASI TRANSAKSI -->
                @php
                    $pendingAcc = \App\Models\Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
                    $notifBadge = $pendingAcc > 99 ? '99+' : $pendingAcc;
                @endphp

                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Operasional</p>
                    <a href="{{ route('admin.transaksi.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.transaksi.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-5 h-5 transition {{ request()->routeIs('admin.transaksi.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Transaksi</span>
                        <!-- Tampil badge merah hanya jika ada yang Menunggu ACC -->
                        @if($pendingAcc > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-3 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse">{{ $notifBadge }}</span>
                        @endif
                    </a>

                    <!-- Laporan (Sudah ada route-nya di bawah nanti) -->
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center p-3 rounded-lg group transition {{ request()->routeIs('admin.laporan.*') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-chart-line w-5 h-5 transition {{ request()->routeIs('admin.laporan.*') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Laporan Penjualan</span>
                    </a>
                </li>

                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Akses Khusus</p>
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center p-3 text-charcoal-900 rounded-lg hover:bg-slate-100 group transition border border-dashed border-slate-300">
                        <i class="fa-solid fa-people-carry-box w-5 h-5 text-slate-400 group-hover:text-gold-600 transition"></i>
                        <span class="ml-3">Mode Karyawan</span>
                    </a>
                </li>
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

    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center bg-slate-900/40 px-4 py-6 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div x-show="deleteModalOpen" x-transition class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <div>
                    <h2 id="delete-modal-title" class="text-lg font-bold text-charcoal-900">Konfirmasi Penghapusan</h2>
                    <p class="mt-1 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <button type="button" @click="deleteModalOpen = false" class="ml-auto text-slate-400 transition hover:text-slate-700" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm leading-relaxed text-slate-600" x-text="deleteMessage"></p>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button" @click="deleteModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    Batal
                </button>
                <button type="button" @click="deleteForm.submit()" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-600/20">
                    Hapus Data
                </button>
            </div>
        </div>
    </div>

    <div x-show="actionModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center bg-slate-900/40 px-4 py-6 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="action-modal-title">
        <div x-show="actionModalOpen" x-transition class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600"><i class="fa-solid fa-circle-question"></i></div>
                <div>
                    <h2 id="action-modal-title" class="text-lg font-bold text-charcoal-900" x-text="actionTitle"></h2>
                    <p class="mt-1 text-sm text-slate-500">Periksa kembali sebelum melanjutkan.</p>
                </div>
                <button type="button" @click="actionModalOpen = false" class="ml-auto text-slate-400 transition hover:text-slate-700" aria-label="Tutup modal"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="px-6 py-5"><p class="text-sm leading-relaxed text-slate-600" x-text="actionMessage"></p></div>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button" @click="actionModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
                <button type="button" @click="actionForm.submit()" class="rounded-lg bg-charcoal-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-charcoal-800 focus:outline-none focus:ring-4 focus:ring-charcoal-900/20">Lanjutkan</button>
            </div>
        </div>
    </div>

</body>
</html>
