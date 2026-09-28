@extends('template.front')

@section('content')
@php
    $steps = [
        'Menunggu ACC' => 1,
        'Dimasak' => 2,
        'Diantar' => 3,
        'Selesai' => 4,
    ];
    $currentStep = $steps[$transaksi->status_pesanan] ?? 1;
    $isActiveOrder = in_array($transaksi->status_pesanan, ['Menunggu ACC', 'Dimasak', 'Diantar']);
@endphp

<section class="relative overflow-hidden bg-charcoal-950 py-10 border-b-4 border-gold-600">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Pesanan seafood Niskala Samudra" class="h-full w-full object-cover opacity-75">
        <div class="absolute inset-0 bg-charcoal-950/60"></div>
    </div>
    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('front.pesanan') }}" class="inline-flex items-center gap-2 text-sm text-slate-200 hover:text-gold-400 transition"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pesanan</a>

            @if($isActiveOrder)
                <div x-data="{ countdown: 25 }" x-init="setInterval(() => { if (countdown > 1) { countdown--; } else { window.location.reload(); } }, 1000)" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-xs font-semibold backdrop-blur-md self-start sm:self-auto">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Live Tracking · Memperbarui dalam <strong x-text="countdown" class="font-mono">25</strong>s</span>
                </div>
            @endif
        </div>

        <p class="mt-4 sm:mt-6 text-[10px] sm:text-xs font-bold uppercase tracking-[0.2em] text-gold-500">Detail & Pelacakan Pesanan</p>
        <h1 class="mt-1.5 sm:mt-2 font-serif text-xl sm:text-3xl lg:text-4xl text-white break-all sm:break-normal">{{ $transaksi->kode_transaksi }}</h1>
        <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-slate-300">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB · Layanan: <strong>{{ $transaksi->tipe_pesanan }}</strong></p>
    </div>
</section>

<section class="min-h-[60vh] bg-slate-50 py-10 sm:py-14">
    <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

        <!-- Visual Stepper Progress Bar -->
        @if($transaksi->status_pesanan === 'Dibatalkan')
            <div class="rounded-2xl border border-red-200 bg-red-50 p-6 shadow-sm flex items-center gap-4 text-red-700">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base">Pesanan Telah Dibatalkan</h3>
                    <p class="text-xs text-red-600 mt-0.5">Pesanan ini telah dibatalkan oleh pihak restoran atau pelanggan. Hubungi kami jika terdapat kendala.</p>
                </div>
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="font-serif text-lg sm:text-xl font-bold text-charcoal-900">Status Alur Pesanan</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau tahapan penyiapan makanan Anda secara berkala.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $transaksi->status_pesanan === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gold-50 text-gold-700 border border-gold-200' }}">
                        <i class="fa-solid fa-circle text-[8px] {{ $transaksi->status_pesanan === 'Selesai' ? 'text-emerald-500' : 'text-gold-500 animate-pulse' }}"></i>
                        {{ $transaksi->status_pesanan }}
                    </span>
                </div>

                <div class="relative">
                    <!-- Progress Line -->
                    <div class="hidden sm:block absolute top-1/2 left-0 right-0 h-1 bg-slate-100 -translate-y-1/2 -z-0">
                        <div class="h-full bg-gold-500 transition-all duration-700" style="width: {{ (($currentStep - 1) / 3) * 100 }}%"></div>
                    </div>

                    <!-- Steps Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-2 relative z-10">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center text-center p-3 rounded-xl transition {{ $currentStep >= 1 ? 'bg-gold-50/50 sm:bg-transparent' : 'opacity-50' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-base font-bold shadow-sm mb-3 transition-all
                                {{ $currentStep > 1 ? 'bg-emerald-500 text-white' : ($currentStep === 1 ? 'bg-gold-500 text-white ring-4 ring-gold-500/20' : 'bg-slate-100 text-slate-400') }}">
                                <i class="fa-solid {{ $currentStep > 1 ? 'fa-check' : 'fa-receipt' }}"></i>
                            </div>
                            <span class="text-xs font-bold text-charcoal-900">1. Diterima</span>
                            <span class="text-[11px] text-slate-500 mt-0.5">Menunggu ACC Kasir</span>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex flex-col items-center text-center p-3 rounded-xl transition {{ $currentStep >= 2 ? 'bg-gold-50/50 sm:bg-transparent' : 'opacity-50' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-base font-bold shadow-sm mb-3 transition-all
                                {{ $currentStep > 2 ? 'bg-emerald-500 text-white' : ($currentStep === 2 ? 'bg-gold-500 text-white ring-4 ring-gold-500/20 animate-pulse' : 'bg-slate-100 text-slate-400') }}">
                                <i class="fa-solid {{ $currentStep > 2 ? 'fa-check' : 'fa-fire-burner' }}"></i>
                            </div>
                            <span class="text-xs font-bold text-charcoal-900">2. Dapur</span>
                            <span class="text-[11px] text-slate-500 mt-0.5">Sedang Dimasak</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex flex-col items-center text-center p-3 rounded-xl transition {{ $currentStep >= 3 ? 'bg-gold-50/50 sm:bg-transparent' : 'opacity-50' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-base font-bold shadow-sm mb-3 transition-all
                                {{ $currentStep > 3 ? 'bg-emerald-500 text-white' : ($currentStep === 3 ? 'bg-gold-500 text-white ring-4 ring-gold-500/20 animate-pulse' : 'bg-slate-100 text-slate-400') }}">
                                <i class="fa-solid {{ $currentStep > 3 ? 'fa-check' : ($transaksi->tipe_pesanan === 'Delivery' ? 'fa-motorcycle' : 'fa-bell-concierge') }}"></i>
                            </div>
                            <span class="text-xs font-bold text-charcoal-900">3. {{ $transaksi->tipe_pesanan === 'Delivery' ? 'Pengantaran' : 'Siap Saji' }}</span>
                            <span class="text-[11px] text-slate-500 mt-0.5">{{ $transaksi->tipe_pesanan === 'Delivery' ? 'Kurir Menuju Lokasi' : 'Dihidangkan ke Meja' }}</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex flex-col items-center text-center p-3 rounded-xl transition {{ $currentStep >= 4 ? 'bg-emerald-50/50 sm:bg-transparent' : 'opacity-50' }}">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-base font-bold shadow-sm mb-3 transition-all
                                {{ $currentStep >= 4 ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : 'bg-slate-100 text-slate-400' }}">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="text-xs font-bold text-charcoal-900">4. Selesai</span>
                            <span class="text-[11px] text-slate-500 mt-0.5">Santapan Dinikmati</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Status Pesanan</p>
                <p class="mt-2 font-bold text-charcoal-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-utensils text-gold-500"></i> {{ $transaksi->status_pesanan }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Pembayaran</p>
                <p class="mt-2 font-bold text-base {{ $transaksi->status_pembayaran === 'Lunas' ? 'text-emerald-600' : 'text-red-500' }} flex items-center gap-2">
                    <i class="fa-solid {{ $transaksi->status_pembayaran === 'Lunas' ? 'fa-circle-check' : 'fa-clock' }}"></i>
                    {{ $transaksi->status_pembayaran }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Metode Bayar</p>
                <p class="mt-2 font-bold text-charcoal-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-gold-500"></i> {{ $transaksi->metode_pembayaran }}
                </p>
            </div>
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

        <div class="flex flex-col sm:flex-row flex-wrap gap-3">
            <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-charcoal-900 hover:bg-slate-50 transition shadow-xs"><i class="fa-solid fa-receipt"></i> Lihat Struk</a>
            @if($transaksi->metode_pembayaran === 'QRIS' && $transaksi->status_pembayaran !== 'Lunas')
                <a href="{{ URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gold-600 px-5 py-3 text-sm font-bold text-white hover:bg-gold-500 transition shadow-xs"><i class="fa-solid fa-wallet"></i> Lanjutkan Pembayaran</a>
            @endif
        </div>
    </div>
</section>
@endsection
