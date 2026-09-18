@extends('template.front')

@section('content')
<section class="relative overflow-hidden bg-charcoal-950 py-10 border-b-4 border-gold-600">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Pesanan seafood Niskala Samudra" class="h-full w-full object-cover opacity-75">
        <div class="absolute inset-0 bg-charcoal-950/45"></div>
    </div>
    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <a href="{{ route('front.pesanan') }}" class="inline-flex items-center gap-2 text-sm text-slate-200 hover:text-gold-400 transition"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pesanan</a>
        <p class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-gold-500">Detail Pesanan</p>
        <h1 class="mt-2 font-serif text-3xl text-white sm:text-4xl">{{ $transaksi->kode_transaksi }}</h1>
        <p class="mt-2 text-sm text-slate-200">{{ $transaksi->created_at->format('d M Y, H:i') }} · {{ $transaksi->tipe_pesanan }}</p>
    </div>
</section>

<section class="min-h-[60vh] bg-slate-50 py-10 sm:py-14">
    <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs uppercase tracking-wider text-slate-400">Status Pesanan</p><p class="mt-2 font-bold text-charcoal-900">{{ $transaksi->status_pesanan }}</p></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs uppercase tracking-wider text-slate-400">Pembayaran</p><p class="mt-2 font-bold {{ $transaksi->status_pembayaran === 'Lunas' ? 'text-emerald-600' : 'text-red-500' }}">{{ $transaksi->status_pembayaran }}</p></div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-xs uppercase tracking-wider text-slate-400">Metode</p><p class="mt-2 font-bold text-charcoal-900">{{ $transaksi->metode_pembayaran }}</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-serif text-lg font-bold text-charcoal-900">Informasi Pesanan</h2>
                <div class="mt-5 space-y-4 text-sm">
                    <div><p class="text-xs text-slate-400">Nama Pemesan</p><p class="mt-1 font-medium text-charcoal-900">{{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan }}</p></div>
                    <div><p class="text-xs text-slate-400">Nomor HP</p><p class="mt-1 text-charcoal-900">{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan ?? 'Belum tersedia' }}</p></div>
                    @if($transaksi->alamat)
                        <div><p class="text-xs text-slate-400">Alamat Pengiriman</p><p class="mt-1 leading-5 text-charcoal-900">{{ $transaksi->alamat }}</p></div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-serif text-lg font-bold text-charcoal-900">Rincian Pesanan</h2></div>
                <div class="divide-y divide-slate-100">
                    @foreach($transaksi->detail as $detail)
                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <div><p class="font-semibold text-charcoal-900">{{ $detail->menu->nama_menu ?? $detail->nama_menu }}</p><p class="mt-1 text-xs text-slate-500">{{ $detail->qty }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p></div>
                            <p class="font-bold text-gold-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 border-t border-slate-200 bg-slate-50 px-5 py-4 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span>Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</span></div>
                    @if($transaksi->ongkos_kirim > 0)<div class="flex justify-between"><span class="text-slate-500">Ongkos kirim</span><span>Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</span></div>@endif
                    <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold"><span>Total</span><span class="text-gold-600">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>

        @if($transaksi->catatan)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><h2 class="font-bold text-charcoal-900">Catatan Pesanan</h2><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $transaksi->catatan }}</p></div>
        @endif

        <div class="flex flex-wrap gap-3">
            <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-charcoal-900 hover:bg-slate-50"><i class="fa-solid fa-receipt"></i> Lihat Struk</a>
            @if($transaksi->metode_pembayaran === 'QRIS' && $transaksi->status_pembayaran !== 'Lunas')
                <a href="{{ URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" class="inline-flex items-center gap-2 rounded-xl bg-gold-600 px-5 py-3 text-sm font-bold text-white hover:bg-gold-500"><i class="fa-solid fa-wallet"></i> Lanjutkan Pembayaran</a>
            @endif
        </div>
    </div>
</section>
@endsection
