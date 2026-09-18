@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Pos Kurir -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-500 rounded-xl shrink-0">
                <i class="fa-solid fa-motorcycle text-xl animate-bounce"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pengantaran (Delivery)</h1>
                <p class="text-sm text-slate-500 mt-1">Daftar pesanan yang siap diantar ke lokasi pelanggan.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('karyawan.delivery') }}" method="GET" class="w-full sm:w-64 relative">
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

    <!-- Kanban Grid Kurir -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($transaksi as $trx)
            <div class="bg-white border-2 border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md relative">

                <!-- Garis Biru Tanda Delivery -->
                <div class="absolute top-0 left-0 w-full h-1 bg-linear-to-r from-blue-400 to-blue-600"></div>

                <!-- Header Card -->
                <div class="p-4 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
                    <div>
                        <h3 class="font-mono font-bold text-charcoal-900 text-lg leading-none">{{ $trx->kode_transaksi }}</h3>
                        <p class="text-xs text-slate-500 mt-1"><i class="fa-regular fa-clock"></i> Masuk: {{ $trx->created_at->format('H:i') }}</p>
                    </div>
                    <div class="text-right">
                        @if($trx->status_pembayaran == 'Lunas')
                            <span class="inline-flex px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase tracking-wider"><i class="fa-solid fa-check mr-1"></i> Lunas</span>
                        @else
                            <span class="inline-flex px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded uppercase tracking-wider animate-pulse"><i class="fa-solid fa-hand-holding-dollar mr-1"></i> Tagih (COD)</span>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex-1 bg-white space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div><p class="text-xs font-bold uppercase tracking-wider text-slate-400">Penerima</p><p class="font-bold text-charcoal-900 truncate">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p></div>
                        <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"><i class="fa-solid fa-eye"></i></a>
                    </div>
                    <div class="rounded-xl bg-blue-50 border border-blue-100 px-3 py-3">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider"><i class="fa-solid fa-location-dot mr-1"></i> Tujuan</p>
                        <p class="mt-1 truncate text-sm font-medium text-charcoal-900">{{ $trx->alamat ?? 'Alamat tidak dicantumkan' }}</p>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-500"><span>{{ $trx->detail->sum('qty') }} Porsi</span><span>{{ $trx->status_pembayaran }}</span></div>

                </div>

                <!-- Footer Card (Aksi) -->
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pengantaran Selesai', 'Pastikan pesanan sudah diserahkan kepada pelanggan.')">
                        @csrf

                        <input type="hidden" name="status_pesanan" value="Selesai">
                        <!-- Jika COD, saat kurir menyelesaikan pesanan, status pembayaran otomatis jadi Lunas -->
                        @if($trx->status_pembayaran != 'Lunas')
                            <input type="hidden" name="status_pembayaran" value="Lunas">

                            <div class="flex items-center justify-between mb-3 px-3 py-2 bg-red-50 border border-red-100 rounded-lg">
                                <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Tagih (COD)</span>
                                <span class="font-bold text-red-700 text-lg">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button type="submit" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-600/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-flag-checkered"></i> Selesaikan Pengantaran
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
                <i class="fa-solid fa-motorcycle text-6xl text-slate-200 mb-4"></i>
                <h3 class="text-xl font-serif text-charcoal-900 mb-2">Tidak Ada Antrian Delivery</h3>
                <p class="text-slate-500">Semua pesanan sudah diantar. Auto-refresh aktif setiap 30 detik.</p>
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
