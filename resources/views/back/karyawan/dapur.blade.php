@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-50 text-orange-500 rounded-xl shrink-0">
                <i class="fa-solid fa-fire-burner text-xl animate-bounce"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Stasiun Dapur</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar antrian pesanan yang harus segera dimasak (FIFO).</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('karyawan.dapur') }}" method="GET" class="w-full sm:w-64 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan..." class="w-full bg-white border border-slate-200 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-sm">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </form>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider hidden sm:block">Auto Refresh: <span class="text-emerald-500">ON</span></span>
                <button onclick="window.location.reload();" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-gold-600 transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-500 mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($transaksi as $trx)
            <div class="bg-yellow-50/30 border-2 border-orange-200 rounded-2xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md relative">

                <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-orange-400 to-red-500"></div>

                <div class="p-4 border-b border-orange-200/50 flex justify-between items-start bg-white">
                    <div>
                        <h3 class="font-mono font-bold text-charcoal-900 text-xl leading-none">{{ $trx->kode_transaksi }}</h3>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider mt-2
                            {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-blue-100 text-blue-700' : ($trx->tipe_pesanan == 'Delivery' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ $trx->tipe_pesanan }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Tunggu</p>
                        <p class="font-bold text-red-600 text-sm animate-pulse"><i class="fa-regular fa-clock"></i> {{ $trx->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="p-4 flex-1 bg-white space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div><p class="text-xs text-slate-400">Pelanggan</p><p class="font-bold text-charcoal-900 truncate">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p></div>
                        <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"><i class="fa-solid fa-eye"></i></a>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-orange-50 px-3 py-3 text-sm"><span class="text-orange-700"><i class="fa-solid fa-utensils mr-1"></i> Total menu</span><strong class="text-orange-800">{{ $trx->detail->sum('qty') }} Porsi</strong></div>
                    @if($trx->catatan)
                        <p class="truncate text-xs font-semibold text-red-600"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Ada catatan dapur</p>
                    @endif
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pesanan Selesai Dimasak', 'Pastikan semua pesanan sudah matang dan siap disajikan.')">
                        @csrf

                        <!-- Logika Cerdas: Jika Delivery diteruskan ke Kurir, Jika Makan di Tempat langsung Selesai -->
                        <input type="hidden" name="status_pesanan" value="{{ $trx->tipe_pesanan == 'Delivery' ? 'Diantar' : 'Selesai' }}">

                        <button type="submit" class="w-full py-3 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition shadow-sm shadow-orange-500/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bell-concierge"></i> Pesanan Selesai Dimasak
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
                <i class="fa-solid fa-kitchen-set text-6xl text-slate-200 mb-4"></i>
                <h3 class="text-xl font-serif text-charcoal-900 mb-2">Dapur Kosong</h3>
                <p class="text-slate-500">Belum ada pesanan yang perlu dimasak. Auto-refresh aktif.</p>
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
