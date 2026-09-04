@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Pos Kasir -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-red-50 text-red-500 rounded-xl shrink-0">
                <i class="fa-solid fa-bell-concierge text-xl animate-pulse"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pos Kasir: Pesanan Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Cek pembayaran dan setujui pesanan untuk diteruskan ke dapur.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <!-- Form Pencarian -->
            <form action="{{ route('karyawan.pesanan_baru') }}" method="GET" class="w-full sm:w-64 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi..." class="w-full bg-white border border-slate-200 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-sm">
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

    <!-- Kanban Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($transaksi as $trx)
            <div class="bg-white border-2 {{ $trx->status_pembayaran == 'Menunggu Konfirmasi' ? 'border-gold-400 shadow-gold-500/20' : 'border-slate-200' }} rounded-2xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md">

                <!-- Card Header -->
                <div class="p-4 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider mb-2
                            {{ $trx->tipe_pesanan == 'Dine-In' ? 'bg-blue-100 text-blue-700' : ($trx->tipe_pesanan == 'Delivery' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ $trx->tipe_pesanan }}
                        </span>
                        <h3 class="font-mono font-bold text-charcoal-900 text-lg leading-none">{{ $trx->kode_transaksi }}</h3>
                        <p class="text-xs text-slate-500 mt-1"><i class="fa-regular fa-clock"></i> {{ $trx->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total</p>
                        <p class="font-bold text-gold-600 text-lg">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Card Body (Info Pelanggan & Catatan) -->
                <div class="p-4 flex-1">
                    <p class="text-sm font-bold text-charcoal-900 mb-1"><i class="fa-solid fa-user text-slate-400 mr-1"></i> {{ $trx->pelanggan->nama ?? 'Guest' }}</p>

                    @if($trx->catatan)
                        <div class="mt-3 p-3 bg-yellow-50/50 border border-yellow-100 rounded-xl text-xs text-charcoal-800">
                            {!! nl2br(e($trx->catatan)) !!}
                        </div>
                    @endif

                    <!-- List Menu Singkat -->
                    <div class="mt-4 pt-4 border-t border-slate-100 border-dashed space-y-2">
                        @foreach($trx->detail as $dt)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600"><span class="font-bold text-charcoal-900">{{ $dt->qty }}x</span> {{ $dt->menu->nama_menu ?? 'Menu Terhapus' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Card Footer (Status Bayar & Tombol Aksi) -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 space-y-3">

                    <!-- Info Pembayaran -->
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-semibold text-slate-600"><i class="fa-solid fa-wallet text-slate-400 mr-1"></i> {{ $trx->metode_pembayaran }}</span>

                        @if($trx->status_pembayaran == 'Menunggu Konfirmasi')
                            <span class="px-2 py-1 bg-gold-100 text-gold-800 text-[10px] font-bold rounded animate-pulse uppercase tracking-wider">Cek Bukti</span>
                        @elseif($trx->status_pembayaran == 'Lunas')
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded uppercase tracking-wider">Lunas</span>
                        @else
                            <span class="px-2 py-1 bg-slate-200 text-slate-600 text-[10px] font-bold rounded uppercase tracking-wider">Belum Lunas</span>
                        @endif
                    </div>

                    <!-- Tombol Aksi Kasir -->
                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <!-- Tombol Batal -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Dibatalkan">
                            <input type="hidden" name="status_pembayaran" value="Batal">
                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" class="w-full py-2.5 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-xl hover:bg-red-50 transition">
                                Batalkan
                            </button>
                        </form>

                        <!-- Tombol ACC Sesuai Kondisi -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Dimasak">

                            @if($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Belum Lunas')
                                <button type="button" disabled class="w-full py-2.5 bg-slate-200 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                    Tunggu Bukti
                                </button>

                            @elseif($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Menunggu Konfirmasi')
                                <input type="hidden" name="status_pembayaran" value="Lunas">
                                <a href="{{ asset('storage/' . $trx->bukti_pembayaran) }}" target="_blank" class="block w-full py-2 mb-2 bg-charcoal-900 text-white text-xs font-bold rounded-xl text-center hover:bg-charcoal-800">
                                    <i class="fa-solid fa-image mr-1"></i> Lihat Bukti
                                </a>
                                <button type="submit" onclick="return confirm('Bukti sudah valid? Teruskan ke Dapur?')" class="w-full py-2.5 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:bg-emerald-600 transition shadow-sm">
                                    Valid & ACC Dapur
                                </button>

                            @else
                                <input type="hidden" name="status_pembayaran" value="{{ $trx->metode_pembayaran == 'Tunai' ? 'Lunas' : 'Belum Lunas' }}">
                                <button type="submit" class="w-full py-2.5 bg-gold-600 text-white text-xs font-bold rounded-xl hover:bg-gold-500 transition shadow-sm shadow-gold-600/20">
                                    {{ $trx->metode_pembayaran == 'Tunai' ? 'Terima Uang & ACC' : 'ACC Dapur (COD)' }}
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
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

<!-- Auto Refresh Script (Halaman otomatis ke-reload setiap 30 detik, termasuk dengan pencarian/halaman) -->
<script>
    setTimeout(function() {
        window.location.reload();
    }, 30000); // 30000 ms = 30 detik
</script>
@endsection
