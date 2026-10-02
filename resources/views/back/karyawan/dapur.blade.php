@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3.5">
            <div class="inline-flex items-center justify-center w-11 h-11 bg-amber-50 text-amber-600 rounded-xl shrink-0 border border-amber-100">
                <i class="fa-solid fa-fire-burner text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-950">Stasiun Dapur</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Daftar antrian pesanan yang harus segera dimasak (FIFO).</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('karyawan.dapur') }}" method="GET" class="w-full sm:w-64 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-xs">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <span class="text-xs font-semibold text-slate-400 hidden sm:block">Auto Refresh: <span class="text-emerald-600">ON</span></span>
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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($transaksi as $trx)
            <div class="bg-white border border-amber-200/80 rounded-2xl shadow-xs overflow-hidden flex flex-col transition hover:shadow-md">

                <div class="p-4 border-b border-slate-100 flex justify-between items-start bg-amber-50/30">
                    <div>
                        <h3 class="font-mono font-bold text-charcoal-950 text-base sm:text-lg leading-none">{{ $trx->kode_transaksi }}</h3>
                        <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-semibold uppercase tracking-wider mt-2
                            {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-sky-50 text-sky-700 border border-sky-200/60' : ($trx->tipe_pesanan == 'Delivery' ? 'bg-purple-50 text-purple-700 border border-purple-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60') }}">
                            {{ $trx->tipe_pesanan }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-0.5">Waktu Tunggu</p>
                        <p class="font-semibold text-amber-700 text-xs sm:text-sm flex items-center justify-end gap-1"><i class="fa-regular fa-clock text-[10px]"></i> {{ $trx->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="p-4 flex-1 bg-white space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div><p class="text-[11px] text-slate-400 font-medium">Pelanggan</p><p class="font-semibold text-charcoal-950 text-xs sm:text-sm truncate">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p></div>
                        <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="group inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-xl bg-slate-50 hover:bg-charcoal-950 text-slate-500 hover:text-gold-400 border border-slate-200/80 hover:border-charcoal-950 transition-all duration-200 shadow-2xs active:scale-95"><i class="fa-solid fa-eye text-xs group-hover:scale-110 transition-transform"></i></a>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-amber-50/70 border border-amber-200/60 px-3.5 py-2.5 text-xs sm:text-sm"><span class="text-amber-800 font-medium flex items-center gap-1.5"><i class="fa-solid fa-utensils text-xs"></i> Total menu</span><strong class="text-amber-900 font-semibold">{{ $trx->detail->sum('qty') }} Porsi</strong></div>
                    @if($trx->catatan)
                        <p class="truncate text-xs font-semibold text-red-600 flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Ada catatan dapur</p>
                    @endif
                </div>

                <div class="p-4 bg-slate-50/70 border-t border-slate-100">
                    <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pesanan Selesai Dimasak', 'Pastikan semua hidangan sudah selesai dimasak dan dikemas dengan rapi.')">
                        @csrf

                        <!-- Logika Cerdas: Jika Delivery masuk antrean Siap Diantar, Jika Makan di Tempat langsung Selesai -->
                        <input type="hidden" name="status_pesanan" value="{{ $trx->tipe_pesanan == 'Delivery' ? 'Siap Diantar' : 'Selesai' }}">

                        <button type="submit" class="group w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-charcoal-950 hover:text-white text-xs sm:text-sm font-semibold rounded-xl transition-all duration-200 shadow-xs flex items-center justify-center gap-2 active:scale-95">
                            @if($trx->tipe_pesanan == 'Delivery')
                                <i class="fa-solid fa-box-open text-xs group-hover:scale-110 transition-transform"></i> <span>Selesai Dimasak (Siap Diantar)</span>
                            @else
                                <i class="fa-solid fa-bell-concierge text-xs group-hover:scale-110 transition-transform"></i> <span>Selesai Dimasak & Sajikan</span>
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-16 bg-white rounded-2xl shadow-xs border border-slate-200/80">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl mx-auto mb-3 border border-amber-100">
                    <i class="fa-solid fa-kitchen-set"></i>
                </div>
                <h3 class="text-lg font-serif font-bold text-charcoal-950 mb-1">Dapur Kosong</h3>
                <p class="text-slate-500 text-xs sm:text-sm max-w-xs mx-auto">Belum ada pesanan yang perlu dimasak. Halaman akan otomatis memuat data baru.</p>
            </div>
        @endforelse
    </div>

    @if($transaksi->hasPages())
        <div class="mt-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
            {{ $transaksi->links() }}
        </div>
    @endif

</div>

<script>
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>
@endsection
