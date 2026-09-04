@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Banner Welcome & Live Clock -->
    <div class="bg-charcoal-950 rounded-2xl p-6 sm:p-8 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-10 pointer-events-none">
            <i class="fa-solid fa-mug-hot text-9xl"></i>
        </div>

        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-wide">
                Halo, <span class="text-gold-500">{{ Auth::user()->nama ?? 'Karyawan' }}</span>! 👋
            </h1>
            <p class="text-slate-400 mt-2 text-sm sm:text-base">
                Selamat bertugas di Shift hari ini. Mari berikan pelayanan terbaik untuk pelanggan Niskala Samudra.
            </p>
        </div>

        <!-- Live Clock Component (AlpineJS) -->
        <div class="relative z-10 flex-shrink-0 w-full md:w-auto">
            <div class="bg-charcoal-900 border border-charcoal-800 px-6 py-3 rounded-xl text-center shadow-inner">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-3xl font-bold text-gold-400 font-mono tracking-wider"
                   x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                   x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)"
                   x-text="time"></p>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Pesanan Baru -->
        <div class="bg-white border {{ $pesananBaru > 0 ? 'border-red-200 shadow-red-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-sm relative overflow-hidden">
            @if($pesananBaru > 0) <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Antrian Kasir</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananBaru }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananBaru > 0 ? 'bg-red-500 text-white animate-bounce' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
        </div>

        <!-- Antrian Dapur -->
        <div class="bg-white border {{ $pesananDapur > 0 ? 'border-orange-200 shadow-orange-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-sm relative overflow-hidden">
            @if($pesananDapur > 0) <div class="absolute top-0 left-0 w-full h-1 bg-orange-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Antrian Dapur</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananDapur }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananDapur > 0 ? 'bg-orange-50 text-orange-500' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
        </div>

        <!-- Antrian Delivery -->
        <div class="bg-white border {{ $pesananDelivery > 0 ? 'border-blue-200 shadow-blue-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-sm relative overflow-hidden">
            @if($pesananDelivery > 0) <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siap Antar</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananDelivery }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananDelivery > 0 ? 'bg-blue-50 text-blue-500' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
        </div>

        <!-- Peringatan Stok -->
        <div class="bg-white border {{ $stokMenipis > 0 ? 'border-purple-200 shadow-purple-500/10' : 'border-slate-200' }} rounded-2xl p-6 shadow-sm relative overflow-hidden">
            @if($stokMenipis > 0) <div class="absolute top-0 left-0 w-full h-1 bg-purple-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stok Menipis</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $stokMenipis }} <span class="text-sm font-normal text-slate-500">Menu</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $stokMenipis > 0 ? 'bg-purple-50 text-purple-500 animate-pulse' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Jalan Pintas Operasional & Alert Stok -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Shortcut Buttons -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-charcoal-900 mb-4 border-b border-slate-100 pb-3">Akses Cepat Operasional</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <a href="{{ route('karyawan.pesanan_baru') }}" class="flex items-center p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-red-50 hover:border-red-200 transition group">
                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cash-register"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 group-hover:text-red-700 transition">Pos Kasir</h4>
                        <p class="text-xs text-slate-500">Kelola pesanan baru</p>
                    </div>
                </a>

                <a href="{{ route('karyawan.dapur') }}" class="flex items-center p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-orange-50 hover:border-orange-200 transition group">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-kitchen-set"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 group-hover:text-orange-700 transition">Stasiun Dapur</h4>
                        <p class="text-xs text-slate-500">Lihat antrian masak</p>
                    </div>
                </a>

                <a href="{{ route('karyawan.delivery') }}" class="flex items-center p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition group">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-motorcycle"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 group-hover:text-blue-700 transition">Pengantaran</h4>
                        <p class="text-xs text-slate-500">Kirim pesanan delivery</p>
                    </div>
                </a>

                <a href="{{ route('karyawan.stok') }}" class="flex items-center p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-purple-50 hover:border-purple-200 transition group">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-500 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 group-hover:text-purple-700 transition">Gudang & Stok</h4>
                        <p class="text-xs text-slate-500">Input stok menu masuk</p>
                    </div>
                </a>

            </div>
        </div>

        <!-- Alert Stok Menipis -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                <h3 class="font-bold text-charcoal-900">Perhatian Stok</h3>
                @if($stokMenipis > 0)
                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold rounded uppercase animate-pulse">Action Required</span>
                @endif
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                @forelse($menuMenipis as $menu)
                    <div class="flex justify-between items-center p-3 rounded-lg border {{ $menu->stok == 0 ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }}">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-charcoal-900">{{ $menu->nama_menu }}</p>
                            <p class="text-xs text-slate-500">{{ $menu->kategori->nama_kategori ?? 'Umum' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-md font-bold text-sm {{ $menu->stok == 0 ? 'bg-red-500 text-white' : 'bg-yellow-400 text-charcoal-900' }}">
                                {{ $menu->stok }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center py-6 text-slate-400">
                        <i class="fa-solid fa-box-check text-4xl mb-3 text-emerald-300"></i>
                        <p class="text-sm font-medium">Semua stok menu aman.</p>
                    </div>
                @endforelse
            </div>

            @if($stokMenipis > 5)
                <a href="{{ route('karyawan.stok') }}" class="mt-4 block w-full text-center py-2 bg-slate-50 text-slate-600 hover:bg-slate-100 text-xs font-bold rounded-lg transition border border-slate-200">
                    Lihat {{ $stokMenipis - 5 }} Menu Lainnya
                </a>
            @endif
        </div>

    </div>
</div>
@endsection
