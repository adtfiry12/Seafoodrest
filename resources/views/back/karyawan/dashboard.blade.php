@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Banner Welcome & Live Clock -->
    <div class="bg-charcoal-950 rounded-2xl p-6 sm:p-8 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-10 pointer-events-none">
            <i class="fa-solid fa-mug-hot text-9xl"></i>
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gold-500/20 text-gold-400 border border-gold-500/30">
                    Shift Operasional
                </span>
                <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-300">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Live Data
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-wide">
                Halo, <span class="text-gold-500">{{ Auth::user()->nama ?? 'Karyawan' }}</span>! 👋
            </h1>
            <p class="text-slate-400 mt-2 text-sm sm:text-base max-w-xl">
                Pantau antrean pesanan, status dapur, pengantaran, dan stok menu restoran secara realtime.
            </p>
        </div>

        <!-- Live Clock & Quick Refresh -->
        <div class="relative z-10 flex-shrink-0 w-full md:w-auto flex flex-col sm:flex-row md:flex-col items-center sm:items-end gap-3">
            <div class="bg-charcoal-900 border border-charcoal-800 px-5 py-3 rounded-xl text-center shadow-inner w-full sm:w-auto">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-2xl sm:text-3xl font-bold text-gold-400 font-mono tracking-wider"
                   x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                   x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }), 1000)"
                   x-text="time"></p>
            </div>
            <button onclick="window.location.reload();" class="text-xs text-slate-400 hover:text-gold-400 transition flex items-center gap-1.5 self-center sm:self-end">
                <i class="fa-solid fa-rotate-right"></i> Auto-refresh 30s
            </button>
        </div>
    </div>

    <!-- Quick Stats Cards (Clickable Links) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Antrian Kasir (Menunggu ACC) -->
        <a href="{{ route('karyawan.pesanan_baru') }}" class="group bg-white border {{ $pesananBaru > 0 ? 'border-red-300 shadow-md shadow-red-500/10' : 'border-slate-200' }} rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between">
            @if($pesananBaru > 0) <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Antrian Kasir</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananBaru }}</h3>
                    <p class="text-xs mt-1 {{ $pesananBaru > 0 ? 'text-red-600 font-bold' : 'text-slate-400' }}">
                        {{ $pesananBaru > 0 ? 'Perlu tindakan segera' : 'Tidak ada antrean' }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananBaru > 0 ? 'bg-red-500 text-white animate-bounce' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500 group-hover:text-red-600 transition">
                <span>Kelola Pesanan</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Dapur (Dimasak) -->
        <a href="{{ route('karyawan.dapur') }}" class="group bg-white border {{ $pesananDapur > 0 ? 'border-orange-300 shadow-md shadow-orange-500/10' : 'border-slate-200' }} rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between">
            @if($pesananDapur > 0) <div class="absolute top-0 left-0 w-full h-1 bg-orange-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Antrian Dapur</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananDapur }}</h3>
                    <p class="text-xs mt-1 {{ $pesananDapur > 0 ? 'text-orange-600 font-bold' : 'text-slate-400' }}">
                        {{ $pesananDapur > 0 ? 'Sedang dimasak (FIFO)' : 'Dapur siap melayani' }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananDapur > 0 ? 'bg-orange-50 text-orange-500' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500 group-hover:text-orange-600 transition">
                <span>Stasiun Dapur</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Antrian Delivery (Diantar) -->
        <a href="{{ route('karyawan.delivery') }}" class="group bg-white border {{ $pesananDelivery > 0 ? 'border-blue-300 shadow-md shadow-blue-500/10' : 'border-slate-200' }} rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden transition-all duration-200 hover:-translate-y-1 hover:shadow-lg flex flex-col justify-between">
            @if($pesananDelivery > 0) <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div> @endif
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Siap Antar</p>
                    <h3 class="text-3xl font-bold text-charcoal-900 mt-2">{{ $pesananDelivery }}</h3>
                    <p class="text-xs mt-1 {{ $pesananDelivery > 0 ? 'text-blue-600 font-bold' : 'text-slate-400' }}">
                        {{ $pesananDelivery > 0 ? 'Pesanan dalam perjalanan' : 'Semua terkirim' }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl {{ $pesananDelivery > 0 ? 'bg-blue-50 text-blue-500' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500 group-hover:text-blue-600 transition">
                <span>Pengantaran</span>
                <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- Selesai Hari Ini -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selesai Hari Ini</p>
                    <h3 class="text-3xl font-bold text-emerald-600 mt-2">{{ $pesananSelesaiHariIni }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Pesanan tuntas di shift ini</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-medium text-slate-400">
                <span>Status Pelayanan</span>
                <span class="text-emerald-600 font-bold">Optimal</span>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom Kiri: Antrean Pesanan Aktif Berjalan (2/3) -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gold-50 text-gold-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-lg text-charcoal-900">Antrean Pesanan Aktif</h3>
                        <p class="text-xs text-slate-500">Daftar pesanan yang saat ini sedang dalam proses operasional.</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 w-fit">
                    {{ $pesananAktif->count() }} Pesanan Berjalan
                </span>
            </div>

            <!-- List Pesanan Aktif -->
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pesananAktif as $trx)
                    <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/80 px-2 rounded-xl transition">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" class="font-mono font-bold text-charcoal-900 hover:text-gold-600 transition text-sm sm:text-base">
                                    {{ $trx->kode_transaksi }}
                                </a>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                    {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">
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
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                    @if($trx->status_pesanan == 'Menunggu ACC') bg-red-100 text-red-700
                                    @elseif($trx->status_pesanan == 'Dimasak') bg-orange-100 text-orange-700
                                    @elseif($trx->status_pesanan == 'Diantar') bg-blue-100 text-blue-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ $trx->status_pesanan }}
                                </span>
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-regular fa-clock text-[10px]"></i> {{ $trx->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" class="p-2 sm:px-3 sm:py-2 bg-slate-100 hover:bg-gold-500 hover:text-white rounded-lg text-xs font-bold text-charcoal-900 transition flex items-center gap-1.5 shrink-0">
                                <span>Detail</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <i class="fa-solid fa-clipboard-check text-5xl text-emerald-400 mb-3"></i>
                        <h4 class="font-bold text-charcoal-900 text-base">Semua Pesanan Selesai</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Saat ini belum ada pesanan yang mengantri. Halaman akan otomatis menampilkan data saat ada transaksi baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Peringatan Stok & Akses Cepat (1/3) -->
        <div class="space-y-6">

            <!-- Card Peringatan Stok -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center mb-4 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked text-purple-500"></i>
                        <h3 class="font-bold text-charcoal-900 text-sm">Peringatan Stok</h3>
                    </div>
                    @if($stokMenipis > 0)
                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold rounded uppercase animate-pulse">Perlu Restock</span>
                    @endif
                </div>

                <div class="space-y-2.5 flex-1">
                    @forelse($menuMenipis as $menu)
                        <div class="flex justify-between items-center p-3 rounded-xl border transition {{ $menu->stok == 0 ? 'bg-red-50/70 border-red-200' : 'bg-amber-50/70 border-amber-200' }}">
                            <div class="min-w-0 pr-2">
                                <p class="font-bold text-xs sm:text-sm text-charcoal-900 truncate">{{ $menu->nama_menu }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $menu->kategori->nama_kategori ?? 'Kategori Umum' }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                @if($menu->stok == 0)
                                    <span class="px-2 py-1 rounded bg-red-500 text-white font-bold text-xs uppercase tracking-wider">
                                        Habis
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-lg font-bold text-xs bg-amber-400 text-charcoal-950">
                                        {{ $menu->stok }} porsi
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400">
                            <i class="fa-solid fa-circle-check text-3xl mb-2 text-emerald-400"></i>
                            <p class="text-xs font-medium text-slate-600">Semua stok menu aman (> 5).</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('karyawan.stok') }}" class="mt-4 block w-full text-center py-2.5 bg-slate-50 hover:bg-gold-50 hover:text-gold-700 text-slate-700 text-xs font-bold rounded-xl transition border border-slate-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-boxes-packing"></i> Kelola & Tambah Stok
                </a>
            </div>

            <!-- Card Akses Cepat Operasional (Compact) -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                <h3 class="font-bold text-charcoal-900 text-sm mb-3 pb-2 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-gold-500"></i> Pintasan Kerja
                </h3>
                <div class="grid grid-cols-2 gap-2.5 text-xs font-semibold">
                    <a href="{{ route('karyawan.pesanan_baru') }}" class="p-3 bg-slate-50 hover:bg-red-50 hover:text-red-700 border border-slate-200 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-cash-register text-base text-red-500 group-hover:scale-110 transition-transform"></i>
                        <span>Pos Kasir</span>
                    </a>
                    <a href="{{ route('karyawan.dapur') }}" class="p-3 bg-slate-50 hover:bg-orange-50 hover:text-orange-700 border border-slate-200 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-kitchen-set text-base text-orange-500 group-hover:scale-110 transition-transform"></i>
                        <span>Dapur (FIFO)</span>
                    </a>
                    <a href="{{ route('karyawan.delivery') }}" class="p-3 bg-slate-50 hover:bg-blue-50 hover:text-blue-700 border border-slate-200 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-motorcycle text-base text-blue-500 group-hover:scale-110 transition-transform"></i>
                        <span>Kurir Delivery</span>
                    </a>
                    <a href="{{ route('karyawan.stok') }}" class="p-3 bg-slate-50 hover:bg-purple-50 hover:text-purple-700 border border-slate-200 rounded-xl transition flex flex-col items-center text-center gap-1.5 group">
                        <i class="fa-solid fa-boxes-packing text-base text-purple-500 group-hover:scale-110 transition-transform"></i>
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
