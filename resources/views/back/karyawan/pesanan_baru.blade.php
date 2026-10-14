@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Pos Kasir -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3.5">
            <div class="inline-flex items-center justify-center w-11 h-11 bg-red-50 text-red-600 rounded-xl shrink-0 border border-red-100">
                <i class="fa-solid fa-bell-concierge text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-950">Pos Kasir: Pesanan Baru</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Cek pembayaran dan setujui pesanan untuk diteruskan ke dapur.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <!-- Form Pencarian -->
            <form action="{{ route('karyawan.pesanan_baru') }}" method="GET" class="w-full sm:w-64 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-xs">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-between sm:justify-start">
                <a href="{{ route('karyawan.keranjang') }}" class="px-4 py-2.5 bg-gold-500 hover:bg-gold-600 text-charcoal-950 font-bold rounded-xl text-xs sm:text-sm shadow-xs flex items-center gap-2 transition active:scale-95 shrink-0">
                    <i class="fa-solid fa-cash-register text-xs"></i>
                    <span>Kasir</span>
                </a>
                <button onclick="window.location.reload();" class="group px-4 py-2.5 bg-white border border-slate-200/90 hover:border-gold-300 hover:bg-gold-50/80 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 hover:text-gold-900 transition-all duration-200 shadow-2xs flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-rotate-right text-xs text-gold-600 group-hover:rotate-180 transition-transform duration-500"></i> <span>Refresh</span>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs sm:text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Kanban Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-5 sm:gap-6">
        @forelse($transaksi as $trx)
            <div class="bg-white border {{ $trx->status_pembayaran == 'Menunggu Konfirmasi' ? 'border-amber-300 ring-1 ring-amber-400/20 shadow-xs' : 'border-slate-200/80' }} rounded-2xl shadow-xs overflow-hidden flex flex-col transition hover:shadow-md">

                <!-- Card Header -->
                <div class="p-4 border-b border-slate-100 flex items-start justify-between gap-3 bg-slate-50/60">
                    <div class="min-w-0 flex-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider mb-2
                            {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : ($trx->tipe_pesanan == 'Delivery' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                            {{ $trx->tipe_pesanan }}
                        </span>
                        <h3 class="font-mono font-bold text-charcoal-950 text-base sm:text-lg leading-none truncate">{{ $trx->kode_transaksi }}</h3>
                        <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1 truncate"><i class="fa-regular fa-clock text-[10px]"></i> {{ $trx->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Total</p>
                        <p class="font-bold text-gold-600 font-serif text-lg tabular-nums whitespace-nowrap">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-4 flex-1 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs sm:text-sm font-semibold text-charcoal-950 truncate"><i class="fa-solid fa-user text-slate-400 mr-1.5 text-xs"></i> {{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p>
                        <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="group inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-xl bg-slate-50 hover:bg-charcoal-950 text-slate-500 hover:text-gold-400 border border-slate-200/80 hover:border-charcoal-950 transition-all duration-200 shadow-2xs active:scale-95"><i class="fa-solid fa-eye text-xs group-hover:scale-110 transition-transform"></i></a>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5 text-xs">
                        <div class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-100"><p class="text-slate-400 font-medium">Item</p><p class="mt-0.5 font-semibold text-charcoal-950">{{ $trx->detail->sum('qty') }} Porsi</p></div>
                        <div class="rounded-lg bg-slate-50 px-3 py-2 border border-slate-100"><p class="text-slate-400 font-medium">Pembayaran</p><p class="mt-0.5 font-semibold text-charcoal-950">{{ $trx->status_pembayaran }}</p></div>
                    </div>
                </div>

                <!-- Card Footer (Status Bayar & Tombol Aksi) -->
                <div class="p-4 bg-slate-50/70 border-t border-slate-100 space-y-3">

                    <!-- Info Pembayaran -->
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100 space-y-1.5">
                        <div class="flex justify-between items-center text-xs sm:text-sm">
                            <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                                <i class="fa-solid fa-wallet text-gold-600 text-xs"></i>
                                {{ $trx->payment_channel_label }}
                            </span>

                            @if($trx->status_pembayaran == 'Lunas')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-md uppercase tracking-wider flex items-center gap-1">
                                    <i class="fa-solid fa-check text-[9px]"></i> Lunas
                                </span>
                            @elseif($trx->status_pembayaran == 'Menunggu Konfirmasi')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-semibold rounded-md uppercase tracking-wider">Cek Bukti</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-semibold rounded-md uppercase tracking-wider">
                                    Belum Lunas
                                </span>
                            @endif
                        </div>
                        @if($trx->payment_reference)
                            <div class="flex justify-between items-center text-[11px] text-slate-500 pt-1 border-t border-slate-200/60">
                                <span>No. Referensi:</span>
                                <span class="font-mono font-semibold text-charcoal-900">{{ $trx->payment_reference }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi Kasir -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <!-- Tombol Batal -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pembatalan', 'Yakin ingin membatalkan pesanan ini?')">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Dibatalkan">
                            <input type="hidden" name="status_pembayaran" value="Batal">
                            <button type="submit" class="w-full py-2 bg-white border border-red-200 text-red-600 text-xs font-semibold rounded-xl hover:bg-red-50 transition shadow-xs">
                                Batalkan
                            </button>
                        </form>

                        <!-- Tombol ACC Sesuai Kondisi -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Terima Pesanan', 'Terima pesanan ini untuk diteruskan ke dapur?')">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Dimasak">

                            @if($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Belum Lunas')
                                <button type="button" disabled class="w-full py-2 bg-slate-100 border border-slate-200 text-slate-400 text-xs font-semibold rounded-xl cursor-not-allowed">
                                    Menunggu Bayar
                                </button>

                            @elseif($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Lunas')
                                <input type="hidden" name="status_pembayaran" value="Lunas">
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-fire-burner text-[11px]"></i>
                                    <span>Terima Pesanan</span>
                                </button>

                            @elseif($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Menunggu Konfirmasi')
                                <input type="hidden" name="status_pembayaran" value="Lunas">
                                @if($trx->bukti_pembayaran)
                                    <a href="{{ route('transaksi.bukti', $trx) }}" target="_blank" class="block w-full py-1.5 mb-1.5 bg-charcoal-900 text-white text-xs font-semibold rounded-xl text-center hover:bg-charcoal-800 transition">
                                        <i class="fa-solid fa-image mr-1 text-[10px]"></i> Lihat Bukti
                                    </a>
                                @endif
                                <button type="submit" class="w-full py-2 bg-emerald-600 text-white text-xs font-semibold rounded-xl hover:bg-emerald-700 transition shadow-xs">
                                    Valid & Terima Pesanan
                                </button>

                            @else
                                <input type="hidden" name="status_pembayaran" value="{{ $trx->metode_pembayaran == 'Tunai' ? 'Lunas' : 'Belum Lunas' }}">
                                <button type="submit" class="w-full py-2 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white text-xs font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5" title="Teruskan pesanan ke dapur">
                                    <i class="fa-solid fa-fire-burner text-[11px]"></i>
                                    <span>{{ $trx->metode_pembayaran == 'Tunai' ? 'Terima Pesanan' : 'Terima Pesanan (COD)' }}</span>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
                <i class="fa-solid fa-mug-hot text-6xl text-slate-200 mb-4"></i>
                <h3 class="text-xl font-serif text-charcoal-900 mb-2">Belum Ada Pesanan Baru</h3>
                <p class="text-slate-500">Santai sejenak, halaman ini akan memuat ulang secara otomatis.</p>
            </div>
        @endforelse
    </div>

    <!-- Tampilkan Pagination Jika Lebih dari 6 Data -->
    @if($transaksi->hasPages())
        <div class="mt-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
            {{ $transaksi->links() }}
        </div>
    @endif

</div>

<!-- Auto Refresh Script (Otomatis perbarui antrean setiap 8 detik secara aman) -->
<script>
    if (typeof window.scheduleKaryawanAutoRefresh === 'function') {
        window.scheduleKaryawanAutoRefresh(8);
    }
</script>
@endsection
