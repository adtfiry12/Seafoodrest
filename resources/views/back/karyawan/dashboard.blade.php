@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Layanan & Antrean Pesanan -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/80 font-semibold text-[10px] uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Layanan Restoran Aktif
                </span>
                <span class="text-slate-300">•</span>
                <span class="font-medium text-slate-500 text-xs">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-charcoal-950 tracking-tight">
                Layanan & Antrean Pesanan
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                Kelola konfirmasi kasir, kesiapan hidangan dapur, dan alur pengantaran pesanan tamu.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <!-- Digital Clock with WIB -->
            <div class="bg-white border border-slate-200/90 rounded-xl px-3.5 py-2 shadow-2xs text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Layanan</p>
                <p class="text-sm font-bold font-mono text-charcoal-900 tabular-nums"
                   x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }"
                   x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), 1000)"
                   x-text="time + ' WIB'"></p>
            </div>
            <!-- Quick Manual Refresh -->
            <button onclick="window.location.reload();" title="Perbarui Data Antrean" class="group inline-flex items-center gap-2 px-3.5 py-2.5 bg-white hover:bg-gold-50 hover:text-gold-900 border border-slate-200/90 hover:border-gold-300 text-slate-700 text-xs font-semibold rounded-xl shadow-2xs transition-all duration-200 active:scale-95">
                <i class="fa-solid fa-rotate-right text-xs text-gold-600 group-hover:rotate-180 transition-transform duration-500"></i>
                <span class="hidden sm:inline">Perbarui Antrean</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards (Clickable Links) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Antrian Kasir (Menunggu ACC) -->
        <a href="{{ route('karyawan.pesanan_baru') }}" class="group bg-white border {{ $pesananBaru > 0 ? 'border-red-200 ring-1 ring-red-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Pesanan Masuk (Kasir)</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananBaru }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananBaru > 0 ? 'text-red-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananBaru > 0 ? 'Menunggu konfirmasi kasir' : 'Tidak ada antrean baru' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananBaru > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-red-600 transition">
                <span>Konfirmasi Pesanan</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Dapur (Dimasak) -->
        <a href="{{ route('karyawan.dapur') }}" class="group bg-white border {{ $pesananDapur > 0 ? 'border-amber-200 ring-1 ring-amber-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Proses Dapur</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananDapur }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananDapur > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananDapur > 0 ? 'Sedang dimasak chef (FIFO)' : 'Dapur siap terima order' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananDapur > 0 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-amber-600 transition">
                <span>Buka Stasiun Dapur</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Delivery (Diantar) -->
        <a href="{{ route('karyawan.delivery') }}" class="group bg-white border {{ $pesananDelivery > 0 ? 'border-sky-200 ring-1 ring-sky-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Siap Antar / Saji</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananDelivery }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananDelivery > 0 ? 'text-sky-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananDelivery > 0 ? 'Dalam perjalanan ke tamu' : 'Semua hidangan tersaji' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananDelivery > 0 ? 'bg-sky-50 text-sky-600 border border-sky-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-sky-600 transition">
                <span>Pantau Pengantaran</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Selesai Hari Ini -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Pesanan Tuntas</p>
                    <h3 class="text-3xl font-bold font-serif text-emerald-600 mt-1 tabular-nums">{{ $pesananSelesaiHariIni }}</h3>
                    <p class="text-xs text-slate-400 mt-1.5">Total hidangan tersaji hari ini</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500">
                <span>Status Pelayanan</span>
                <span class="text-emerald-700 font-semibold">Pelayanan Lancar</span>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Antrean Pesanan Aktif Berjalan (2/3) -->
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gold-50 text-gold-700 border border-gold-100 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-lg text-charcoal-950">Daftar Pesanan Berjalan</h3>
                        <p class="text-xs text-slate-500 font-normal">Pesanan aktif yang sedang ditangani di kasir, stasiun dapur, atau pengantaran.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 w-fit">
                    {{ $pesananAktif->count() }} Pesanan Berjalan
                </span>
            </div>

            <!-- List Pesanan Aktif -->
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pesananAktif as $trx)
                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/70 px-2 rounded-xl transition border border-transparent hover:border-slate-100">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" class="font-mono font-bold text-charcoal-950 hover:text-gold-700 transition text-sm">
                                    {{ $trx->kode_transaksi }}
                                </a>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider
                                    {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : 'bg-purple-50 text-purple-700 border border-purple-200/60' }}">
                                    {{ $trx->tipe_pesanan }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                <span class="font-medium text-charcoal-800">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Pelanggan Umum' }}</span>
                                · {{ $trx->detail->count() }} menu pesanan
                            </p>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            <div class="text-left sm:text-right">
                                <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-semibold
                                    @if($trx->status_pesanan == 'Menunggu ACC') bg-red-50 text-red-700 border border-red-200/60
                                    @elseif($trx->status_pesanan == 'Dimasak') bg-amber-50 text-amber-700 border border-amber-200/60
                                    @elseif($trx->status_pesanan == 'Diantar') bg-sky-50 text-sky-700 border border-sky-200/60
                                    @else bg-slate-100 text-slate-700 border border-slate-200/60 @endif">
                                    {{ $trx->status_pesanan }}
                                </span>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-regular fa-clock text-[10px]"></i> {{ $trx->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" class="group inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-charcoal-950 hover:text-white rounded-lg text-xs font-semibold text-charcoal-800 transition-all duration-200 shrink-0 shadow-2xs active:scale-95">
                                <span>Detail</span>
                                <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 group-hover:text-gold-400 group-hover:translate-x-0.5 transition-all"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <i class="fa-solid fa-clipboard-check text-4xl text-emerald-500/70 mb-3"></i>
                        <h4 class="font-bold text-charcoal-900 text-base">Semua Pesanan Selesai</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">Saat ini belum ada pesanan yang mengantri. Halaman akan otomatis menampilkan data saat ada transaksi baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Peringatan Stok & Akses Cepat (1/3) -->
        <div class="space-y-6">

            <!-- Card Peringatan Stok -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-purple-600"></i>
                        <h3 class="font-bold text-charcoal-900 text-sm">Ketersediaan Menu</h3>
                    </div>
                    @if($stokMenipis > 0)
                        <span class="px-2 py-0.5 bg-red-50 text-red-600 border border-red-200 text-[11px] font-semibold rounded-md">Perlu Restock</span>
                    @endif
                </div>

                <div class="space-y-2.5 flex-1">
                    @forelse($menuMenipis as $menu)
                        <div class="flex justify-between items-center p-3 rounded-xl border transition {{ $menu->stok == 0 ? 'bg-red-50/60 border-red-200' : 'bg-amber-50/50 border-amber-200' }}">
                            <div class="min-w-0 pr-2">
                                <p class="font-semibold text-xs sm:text-sm text-charcoal-900 truncate">{{ $menu->nama_menu }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $menu->kategori->nama_kategori ?? 'Kategori Umum' }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                @if($menu->stok == 0)
                                    <span class="px-2 py-0.5 rounded bg-red-600 text-white font-semibold text-[11px] uppercase tracking-wider">
                                        Habis
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-md font-semibold text-xs bg-amber-400/80 text-charcoal-950">
                                        {{ $menu->stok }} porsi
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-2xl mb-1.5 text-emerald-500"></i>
                            <p class="text-xs font-medium text-slate-600">Ketersediaan semua menu aman (> 5 porsi).</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('karyawan.stok') }}" class="mt-4 block w-full text-center py-2.5 bg-slate-50 hover:bg-gold-50 hover:text-gold-800 text-slate-700 text-xs font-semibold rounded-xl transition border border-slate-200/80 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-boxes-packing"></i> Kelola Ketersediaan Menu
                </a>
            </div>

            <!-- Card Akses Cepat Operasional (Compact) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                <h3 class="font-bold text-charcoal-900 text-sm mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-gold-500"></i> Akses Cepat Operasional
                </h3>
                <div class="grid grid-cols-2 gap-2.5 text-xs font-medium">
                    <a href="{{ route('karyawan.pesanan_baru') }}" class="p-3 bg-slate-50/70 hover:bg-red-50/70 hover:text-red-700 border border-slate-200/70 hover:border-red-300 rounded-xl transition-all duration-200 flex flex-col items-center text-center gap-1.5 group hover:shadow-xs hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-cash-register text-base text-red-500 group-hover:scale-110 transition-transform"></i>
                        <span>Meja Kasir</span>
                    </a>
                    <a href="{{ route('karyawan.dapur') }}" class="p-3 bg-slate-50/70 hover:bg-orange-50/70 hover:text-orange-700 border border-slate-200/70 hover:border-orange-300 rounded-xl transition-all duration-200 flex flex-col items-center text-center gap-1.5 group hover:shadow-xs hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-kitchen-set text-base text-orange-500 group-hover:scale-110 transition-transform"></i>
                        <span>Stasiun Dapur</span>
                    </a>
                    <a href="{{ route('karyawan.delivery') }}" class="p-3 bg-slate-50/70 hover:bg-sky-50/70 hover:text-sky-700 border border-slate-200/70 hover:border-sky-300 rounded-xl transition-all duration-200 flex flex-col items-center text-center gap-1.5 group hover:shadow-xs hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-motorcycle text-base text-sky-500 group-hover:scale-110 transition-transform"></i>
                        <span>Pengantaran</span>
                    </a>
                    <a href="{{ route('karyawan.stok') }}" class="p-3 bg-slate-50/70 hover:bg-purple-50/70 hover:text-purple-700 border border-slate-200/70 hover:border-purple-300 rounded-xl transition-all duration-200 flex flex-col items-center text-center gap-1.5 group hover:shadow-xs hover:-translate-y-0.5 active:scale-95">
                        <i class="fa-solid fa-boxes-packing text-base text-purple-500 group-hover:scale-110 transition-transform"></i>
                        <span>Update Stok</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Auto-refresh script (setiap 30 detik agar dashboard selalu realtime) -->
<script>
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>
@endsection
