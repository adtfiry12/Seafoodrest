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
    <style> @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap'); [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <!-- ================= TOP NAVBAR ================= -->
    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-lg sm:hidden hover:bg-charcoal-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center gap-1 ml-2 md:mr-24">
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
                                     src="{{ Auth::user()->foto ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Karyawan').'&background=f59e0b&color=fff' }}" alt="Karyawan Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-4 text-base bg-white divide-y divide-slate-100 rounded shadow border border-slate-200 w-48">
                            <div class="px-4 py-3">
                                <p class="text-sm text-charcoal-900 font-bold truncate">{{ Auth::user()->nama ?? 'Karyawan Resto' }}</p>
                                <p class="text-sm font-medium text-slate-500 truncate">{{ Auth::user()->email ?? 'karyawan@niskalasamudra.com' }}</p>
                            </div>
                            <ul class="py-1">
                                <li><a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-gold-600">Profil Saya</a></li>
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

    <!-- ================= SIDEBAR KARYAWAN ================= -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-slate-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-2 font-medium text-sm">
                <li>
                    <a href="{{ route('dashboard.karyawan') ?? '#' }}" class="flex items-center p-3 text-white rounded-lg bg-charcoal-900 group">
                        <i class="fa-solid fa-house w-5 h-5 text-gold-500 transition duration-75"></i>
                        <span class="ml-3">Ringkasan Shift</span>
                    </a>
                </li>

                <!-- Sesuai Use Case Karyawan -->
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

                <!-- JIKA ADMIN NYASAR KE SINI, MUNCUL TOMBOL KEMBALI -->
                @if(Auth::user() && Auth::user()->role == 'admin')
                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Admin Mode</p>
                    <a href="{{ route('dashboard.admin') ?? '#' }}" class="flex items-center p-3 text-white rounded-lg bg-charcoal-900 hover:bg-charcoal-800 shadow-md group transition">
                        <i class="fa-solid fa-arrow-left-long w-5 h-5 text-gold-500 transition duration-75 group-hover:-translate-x-1"></i>
                        <span class="ml-3">Kembali ke Admin</span>
                    </a>
                </li>
                @endif

            </ul>

            <div class="mt-8 mb-4">
                <a href="{{ route('home') }}" class="flex items-center p-3 text-charcoal-900 rounded-lg bg-gold-50 border border-gold-200 hover:bg-gold-100 group transition shadow-sm">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 h-5 text-gold-600 transition"></i>
                    <span class="ml-3 font-semibold text-gold-700">Website Utama</span>
                </a>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-charcoal-900/50 sm:hidden"></div>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="p-4 sm:ml-64 pt-24">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-charcoal-950 font-serif">Shift Hari Ini</h1>
            <p class="text-sm text-slate-500">Semangat bekerja, {{ Auth::user()->nama ?? 'Karyawan' }}. Cek pesanan yang harus segera diproses.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Menunggu ACC</p>
                        <p class="text-2xl font-bold text-red-700 mt-1">2 <span class="text-sm font-normal text-red-500">Order</span></p>
                    </div>
                    <div class="w-12 h-12 bg-red-200 rounded-full flex items-center justify-center text-red-600"><i class="fa-solid fa-bell text-xl animate-bounce"></i></div>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Sedang Dimasak (Dapur)</p>
                        <p class="text-2xl font-bold text-charcoal-900 mt-1">5 <span class="text-sm font-normal text-slate-400">Order</span></p>
                    </div>
                    <div class="w-12 h-12 bg-gold-100 rounded-full flex items-center justify-center text-gold-600"><i class="fa-solid fa-fire-burner text-xl"></i></div>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Siap Delivery</p>
                        <p class="text-2xl font-bold text-charcoal-900 mt-1">1 <span class="text-sm font-normal text-slate-400">Menunggu Kurir</span></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600"><i class="fa-solid fa-motorcycle text-xl"></i></div>
                </div>
            </div>
            <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Selesai Hari Ini</p>
                        <p class="text-2xl font-bold text-charcoal-900 mt-1">18 <span class="text-sm font-normal text-slate-400">Order</span></p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600"><i class="fa-solid fa-check-double text-xl"></i></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-bold text-charcoal-900">Antrean Pesanan Aktif</h3>
                <a href="#" class="text-sm font-medium text-gold-600 hover:text-gold-700 transition"><i class="fa-solid fa-rotate-right mr-1"></i> Refresh Data</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">No. Order & Waktu</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Tipe Pesanan</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-center">Aksi (ACC/Tolak)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr class="hover:bg-slate-50 transition bg-red-50/30">
                            <td class="px-6 py-4">
                                <span class="font-bold text-charcoal-900">#TRX-026</span><br>
                                <span class="text-xs text-slate-500">Hari ini, 10:10 WIB</span>
                            </td>
                            <td class="px-6 py-4">Delivery (COD) <br><span class="text-xs font-bold text-charcoal-800">Bpk. Reno - 0812xxx</span></td>
                            <td class="px-6 py-4">
                                <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-md border border-red-200">Perlu ACC</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded mr-1 transition"><i class="fa-solid fa-check mr-1"></i> Terima (ACC)</button>
                                <button class="bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs px-3 py-1.5 rounded transition"><i class="fa-solid fa-xmark mr-1"></i> Tolak</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
