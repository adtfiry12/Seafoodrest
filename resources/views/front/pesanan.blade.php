@extends('template.front')

@section('content')

<section class="relative overflow-hidden bg-charcoal-950 py-14 sm:py-20 border-b-4 border-gold-600">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Pesanan seafood Niskala Samudra" class="h-full w-full object-cover opacity-75">
        <div class="absolute inset-0 bg-charcoal-950/45"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.24em] text-gold-500">Niskala Samudra</p>
            <h1 class="font-serif text-4xl leading-tight text-white sm:text-6xl">Pesanan saya</h1>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-slate-300 sm:text-base">Pantau status pesanan, pembayaran, dan riwayat transaksi Anda.</p>
        </div>
    </div>
</section>

<section class="min-h-[60vh] bg-slate-50 py-10 sm:py-14">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        <div class="space-y-6">
            @forelse($transaksi as $trx)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
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

                    <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="grid grid-cols-2 sm:flex sm:items-center gap-x-6 gap-y-3 text-sm">
                            <div><p class="text-xs text-slate-400">Tipe Pesanan</p><p class="mt-1 font-bold text-charcoal-900">{{ $trx->tipe_pesanan }}</p></div>
                            <div><p class="text-xs text-slate-400">Isi Pesanan</p><p class="mt-1 font-bold text-charcoal-900">{{ $trx->detail->sum('qty') }} Porsi</p></div>
                            <div><p class="text-xs text-slate-400">Pembayaran</p><p class="mt-1 font-bold {{ $trx->status_pembayaran === 'Lunas' ? 'text-emerald-600' : 'text-red-500' }}">{{ $trx->status_pembayaran }}</p></div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-3 border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0">
                            <p class="text-lg font-bold text-gold-600">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                            <div class="flex gap-2">
                                <a href="{{ route('front.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="inline-flex items-center justify-center w-9 h-9 bg-charcoal-900 text-white rounded-lg hover:bg-gold-600 transition"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $trx->kode_transaksi]) }}" target="_blank" title="Lihat struk" aria-label="Lihat struk" class="inline-flex items-center justify-center w-9 h-9 bg-slate-100 text-slate-600 rounded-lg border border-slate-200 hover:bg-slate-200 transition"><i class="fa-solid fa-receipt"></i></a>
                                @if($trx->metode_pembayaran == 'QRIS' && $trx->status_pembayaran != 'Lunas')
                                    <a href="{{ URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $trx->kode_transaksi]) }}" title="Bayar dengan QRIS" aria-label="Bayar dengan QRIS" class="inline-flex items-center justify-center w-9 h-9 bg-gold-600 text-white rounded-lg hover:bg-gold-500 transition"><i class="fa-solid fa-wallet"></i></a>
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

        @if($transaksi->hasPages())
            <div class="mt-8">
                {{ $transaksi->links() }}
            </div>
        @endif

    </div>
</section>

@endsection
