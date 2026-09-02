@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-12 border-b-4 border-gold-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl font-serif text-white mb-3">Pesanan Saya</h1>
        <p class="text-slate-400 text-sm font-light">Pantau status pesanan dan riwayat transaksi Anda.</p>
    </div>
</div>

<section class="bg-slate-50 py-12 min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="space-y-6">
            @forelse($transaksi as $trx)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header Transaksi -->
                    <div class="bg-slate-100/50 px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            <p class="font-mono font-bold text-charcoal-900">{{ $trx->kode_transaksi }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($trx->status_pesanan == 'Menunggu ACC')
                                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-clock mr-1"></i> Menunggu</span>
                            @elseif($trx->status_pesanan == 'Dimasak' || $trx->status_pesanan == 'Diantar')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-fire-burner mr-1"></i> Diproses</span>
                            @elseif($trx->status_pesanan == 'Selesai')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-check-double mr-1"></i> Selesai</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider"><i class="fa-solid fa-xmark mr-1"></i> Batal</span>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Transaksi -->
                    <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex-1">
                            <h4 class="font-bold text-charcoal-900 mb-2">{{ $trx->tipe_pesanan }}</h4>
                            <p class="text-sm text-slate-500 mb-1">
                                <span class="font-semibold text-slate-700">Metode:</span> {{ $trx->metode_pembayaran }}
                                <span class="mx-2 text-slate-300">|</span>
                                <span class="font-semibold text-slate-700">Status Bayar:</span>
                                <span class="{{ $trx->status_pembayaran == 'Lunas' ? 'text-emerald-600 font-bold' : 'text-red-500 font-bold' }}">
                                    {{ $trx->status_pembayaran }}
                                </span>
                            </p>
                        </div>

                        <div class="text-left md:text-right w-full md:w-auto border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Total Belanja</p>
                            <p class="text-xl font-bold text-gold-600 mb-4">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('front.struk', $trx->kode_transaksi) }}" target="_blank" class="px-4 py-2 bg-slate-100 text-charcoal-900 text-xs font-bold rounded-lg border border-slate-200 hover:bg-slate-200 transition">
                                    <i class="fa-solid fa-receipt mr-1"></i> Lihat Struk
                                </a>
                                @if($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran != 'Lunas')
                                    <a href="{{ route('checkout.qris', $trx->kode_transaksi) }}" class="px-4 py-2 bg-gold-600 text-white text-xs font-bold rounded-lg hover:bg-gold-500 transition shadow-sm shadow-gold-500/30">
                                        Bayar QRIS
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
                    <i class="fa-solid fa-receipt text-6xl text-slate-300 mb-4"></i>
                    <h3 class="text-xl font-serif text-charcoal-900 mb-2">Belum Ada Transaksi</h3>
                    <p class="text-slate-500 mb-6">Anda belum pernah melakukan pemesanan.</p>
                    <a href="{{ route('menu') }}" class="px-6 py-3 bg-gold-600 text-white text-sm font-bold rounded-xl hover:bg-gold-500 transition">Lihat Menu Sekarang</a>
                </div>
            @endforelse
        </div>

    </div>
</section>

@endsection
