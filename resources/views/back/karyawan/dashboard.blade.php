@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Banner Welcome & Live Clock -->
    <div class="bg-charcoal-950 border border-charcoal-800/80 rounded-2xl p-6 sm:p-7 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm relative overflow-hidden">
        <div class="absolute -right-20 -top-20 w-72 h-72 rounded-full bg-gold-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2.5 mb-2">
                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold tracking-wide bg-gold-500/15 text-gold-400 border border-gold-500/25">
                    Shift Operasional
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs text-slate-300">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Live Data
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-tight text-white">
                Halo, <span class="text-gold-400">{{ Auth::user()->nama ?? 'Karyawan' }}</span>! 👋
            </h1>
            <p class="text-slate-400 mt-1.5 text-sm sm:text-base max-w-xl font-normal leading-relaxed">
                Pantau antrean pesanan, status dapur, pengantaran, dan stok menu restoran secara realtime.
            </p>
        </div>

        <!-- Live Clock & Quick Refresh -->
        <div class="relative z-10 flex-shrink-0 w-full md:w-auto flex flex-col sm:flex-row md:flex-col items-center sm:items-end gap-3">
            <div class="bg-charcoal-900/90 border border-charcoal-800 px-5 py-3 rounded-xl text-center shadow-xs w-full sm:w-auto">
                <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold mb-0.5">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-2xl sm:text-3xl font-bold text-gold-400 font-mono tracking-tight tabular-nums"
                   x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                   x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)"
                   x-text="time"></p>
            </div>
            <button onclick="window.location.reload();" class="text-xs text-slate-400 hover:text-gold-400 transition inline-flex items-center gap-1.5 self-center sm:self-end">
                <i class="fa-solid fa-rotate-right"></i> Auto-refresh 30s
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards (Clickable Links) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Antrian Kasir (Menunggu ACC) -->
        <a href="{{ route('karyawan.pesanan_baru') }}" class="group bg-white border {{ $pesananBaru > 0 ? 'border-red-200 ring-1 ring-red-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Antrian Kasir</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananBaru }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananBaru > 0 ? 'text-red-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananBaru > 0 ? 'Perlu tindakan segera' : 'Tidak ada antrean' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananBaru > 0 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-red-600 transition">
                <span>Kelola Pesanan</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Dapur (Dimasak) -->
        <a href="{{ route('karyawan.dapur') }}" class="group bg-white border {{ $pesananDapur > 0 ? 'border-amber-200 ring-1 ring-amber-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Antrian Dapur</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananDapur }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananDapur > 0 ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananDapur > 0 ? 'Sedang dimasak (FIFO)' : 'Dapur siap melayani' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananDapur > 0 ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-amber-600 transition">
                <span>Stasiun Dapur</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Delivery (Diantar) -->
        <a href="{{ route('karyawan.delivery') }}" class="group bg-white border {{ $pesananDelivery > 0 ? 'border-sky-200 ring-1 ring-sky-500/10 shadow-xs' : 'border-slate-200/80' }} rounded-2xl p-5 shadow-xs relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Siap Antar</p>
                    <h3 class="text-3xl font-bold font-serif text-charcoal-950 mt-1 tabular-nums">{{ $pesananDelivery }}</h3>
                    <p class="text-xs mt-1.5 {{ $pesananDelivery > 0 ? 'text-sky-600 font-semibold' : 'text-slate-400' }}">
                        {{ $pesananDelivery > 0 ? 'Pesanan dalam perjalanan' : 'Semua terkirim' }}
                    </p>
                </div>
                <div class="w-11 h-11 rounded-xl {{ $pesananDelivery > 0 ? 'bg-sky-50 text-sky-600 border border-sky-100' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500 group-hover:text-sky-600 transition">
                <span>Pengantaran</span>
                <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
            </div>
        </a>

        <!-- Selesai Hari Ini -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs relative overflow-hidden flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-slate-500">Selesai Hari Ini</p>
                    <h3 class="text-3xl font-bold font-serif text-emerald-600 mt-1 tabular-nums">{{ $pesananSelesaiHariIni }}</h3>
                    <p class="text-xs text-slate-400 mt-1.5">Pesanan tuntas di shift ini</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-500">
                <span>Status Pelayanan</span>
                <span class="text-emerald-700 font-semibold">Optimal</span>
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
                        <h3 class="font-serif font-bold text-lg text-charcoal-950">Antrean Pesanan Aktif</h3>
                        <p class="text-xs text-slate-500 font-normal">Daftar pesanan yang saat ini sedang dalam proses operasional.</p>
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

                            <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-charcoal-900 hover:text-white rounded-lg text-xs font-semibold text-charcoal-800 transition shrink-0">
                                <span>Detail</span>
                                <i class="fa-solid fa-chevron-right text-[9px]"></i>
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
                        <h3 class="font-bold text-charcoal-900 text-sm">Peringatan Stok</h3>
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
                            <p class="text-xs font-medium text-slate-600">Semua stok menu aman (> 5).</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('karyawan.stok') }}" class="mt-4 block w-full text-center py-2.5 bg-slate-50 hover:bg-gold-50 hover:text-gold-800 text-slate-700 text-xs font-semibold rounded-xl transition border border-slate-200/80 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-boxes-packing"></i> Kelola & Tambah Stok
                </a>
            </div>

            <!-- Card Akses Cepat Operasional (Compact) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-xs">
                <h3 class="font-bold text-charcoal-900 text-sm mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-gold-500"></i> Pintasan Kerja
                </h3>
                <div class="grid grid-cols-2 gap-2.5 text-xs font-medium">
                    <a href="{{ route('karyawan.pesanan_baru') }}" class="p-3 bg-slate-50/70 hover:bg-red-50/60 hover:text-red-700 border border-slate-200/70 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-cash-register text-base text-red-500 group-hover:scale-105 transition-transform"></i>
                        <span>Pos Kasir</span>
                    </a>
                    <a href="{{ route('karyawan.dapur') }}" class="p-3 bg-slate-50/70 hover:bg-orange-50/60 hover:text-orange-700 border border-slate-200/70 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-kitchen-set text-base text-orange-500 group-hover:scale-105 transition-transform"></i>
                        <span>Dapur (FIFO)</span>
                    </a>
                    <a href="{{ route('karyawan.delivery') }}" class="p-3 bg-slate-50/70 hover:bg-sky-50/60 hover:text-sky-700 border border-slate-200/70 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-motorcycle text-base text-sky-500 group-hover:scale-105 transition-transform"></i>
                        <span>Kurir Delivery</span>
                    </a>
                    <a href="{{ route('karyawan.stok') }}" class="p-3 bg-slate-50/70 hover:bg-purple-50/60 hover:text-purple-700 border border-slate-200/70 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-boxes-packing text-base text-purple-500 group-hover:scale-105 transition-transform"></i>
                        <span>Input Stok</span>
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
