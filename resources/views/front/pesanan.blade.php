@extends('template.front')

@section('content')

<section class="relative overflow-hidden bg-charcoal-950 py-10 sm:py-16 border-b border-charcoal-800/80">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Pesanan seafood Niskala Samudra" class="h-full w-full object-cover opacity-60">
        <div class="absolute inset-0 bg-charcoal-950/70"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <span class="text-xs font-semibold uppercase tracking-wider text-gold-400 block mb-1">Riwayat Kuliner</span>
            <h1 class="font-serif text-2xl sm:text-4xl leading-tight text-white font-bold">Pesanan Saya</h1>
            <p class="mt-2 max-w-xl text-xs sm:text-sm leading-relaxed text-slate-300 font-normal">Pantau status pesanan, pembayaran, dan riwayat transaksi bersantap Anda.</p>
        </div>
    </div>
</section>

<section class="min-h-[60vh] bg-slate-50/70 py-10 sm:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-2xl border border-emerald-200/80 bg-emerald-50 p-4 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 rounded-2xl border border-red-200/80 bg-red-50 p-4 text-red-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-base shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="space-y-4 sm:space-y-5">
            @forelse($transaksi as $trx)
                @php
                    $isBatal = in_array($trx->status_pesanan, ['Dibatalkan', 'Batal'], true) || $trx->status_pembayaran === 'Batal';
                @endphp
                <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
                    <div class="bg-slate-50/80 px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium mb-0.5">{{ $trx->created_at->format('d M Y, H:i') }} WIB</p>
                            <p class="font-mono font-bold text-charcoal-950 text-sm sm:text-base">{{ $trx->kode_transaksi }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            @if($trx->status_pembayaran === 'Lunas')
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-check text-[10px]"></i> Lunas
                                </span>
                            @elseif(!$isBatal)
                                <span class="bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5">
                                    <i class="fa-solid fa-wallet text-[10px]"></i> Belum Lunas
                                </span>
                            @endif

                            @if($trx->status_pesanan == 'Menunggu ACC')
                                <span class="bg-amber-50 text-amber-700 border border-amber-200/70 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5"><i class="fa-regular fa-clock text-[10px]"></i> Menunggu</span>
                            @elseif($trx->status_pesanan == 'Dimasak' || $trx->status_pesanan == 'Siap Diantar' || $trx->status_pesanan == 'Diantar')
                                <span class="bg-sky-50 text-sky-700 border border-sky-200/70 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5"><i class="fa-solid fa-fire-burner text-[10px]"></i> {{ $trx->status_pesanan }}</span>
                            @elseif($trx->status_pesanan == 'Selesai')
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200/70 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-[10px]"></i> Selesai</span>
                            @else
                                <span class="bg-red-50 text-red-700 border border-red-200/70 px-2.5 py-1 rounded-md text-[11px] font-semibold flex items-center gap-1.5"><i class="fa-solid fa-xmark text-[10px]"></i> Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="grid grid-cols-3 gap-2 sm:flex sm:items-center sm:gap-6 text-xs sm:text-sm">
                            <div><p class="text-[11px] text-slate-400 font-medium">Layanan</p><p class="mt-0.5 font-semibold text-charcoal-950 truncate">{{ $trx->tipe_pesanan }}</p></div>
                            <div><p class="text-[11px] text-slate-400 font-medium">Porsi</p><p class="mt-0.5 font-semibold text-charcoal-950">{{ $trx->detail->sum('qty') }} Menu</p></div>
                            <div>
                                <p class="text-[11px] text-slate-400 font-medium">Pembayaran</p>
                                <p class="mt-0.5 font-semibold {{ $trx->status_pembayaran === 'Lunas' ? 'text-emerald-600' : ($isBatal ? 'text-red-600' : 'text-amber-600') }}">
                                    {{ $trx->payment_channel_label }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                            <p class="text-base sm:text-lg font-bold text-gold-600 tabular-nums">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                            <div class="flex items-center gap-1.5">
                                @if($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran == 'Belum Lunas' && !$isBatal && $trx->status_pesanan !== 'Selesai')
                                    <a href="{{ URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $trx->kode_transaksi]) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-gold-500 text-charcoal-950 font-bold rounded-lg hover:bg-gold-600 hover:text-white transition shadow-xs text-xs">
                                        <i class="fa-solid fa-wallet"></i>
                                        <span>Bayar Sekarang</span>
                                    </a>
                                @endif
                                <a href="{{ route('front.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="inline-flex items-center justify-center w-9 h-9 bg-charcoal-900 text-white rounded-lg hover:bg-gold-600 transition shadow-xs text-xs"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $trx->kode_transaksi]) }}" target="_blank" title="Lihat struk" aria-label="Lihat struk" class="inline-flex items-center justify-center w-9 h-9 bg-slate-100 text-slate-700 rounded-lg border border-slate-200/80 hover:bg-slate-200 transition shadow-xs text-xs"><i class="fa-solid fa-receipt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl shadow-xs border border-slate-200/80">
                    <div class="w-14 h-14 rounded-2xl bg-gold-50 text-gold-600 flex items-center justify-center text-2xl mx-auto mb-3 border border-gold-100">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <h3 class="text-lg font-serif font-bold text-charcoal-950 mb-1">Belum Ada Transaksi</h3>
                    <p class="text-slate-500 text-xs sm:text-sm mb-5 max-w-xs mx-auto">Anda belum pernah melakukan pemesanan kuliner di Niskala Samudra.</p>
                    <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-charcoal-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-gold-600 transition">
                        <span>Lihat Menu Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            @endforelse
        </div>

        @if($transaksi->hasPages())
            <div class="mt-8">
                {{ $transaksi->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
