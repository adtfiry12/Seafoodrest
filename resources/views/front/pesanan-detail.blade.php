@extends('template.front')

@section('content')
@php
    $isDelivery = $transaksi->tipe_pesanan === 'Delivery';
    if ($isDelivery) {
        $steps = [
            'Menunggu ACC' => 1,
            'Dimasak' => 2,
            'Siap Diantar' => 3,
            'Diantar' => 4,
            'Selesai' => 5,
        ];
        $totalSteps = 5;
    } else {
        $steps = [
            'Menunggu ACC' => 1,
            'Dimasak' => 2,
            'Selesai' => 3,
        ];
        $totalSteps = 3;
    }
    $currentStep = $steps[$transaksi->status_pesanan] ?? 1;
    $isCancelled = in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true) || $transaksi->status_pembayaran === 'Batal';
    $isActiveOrder = in_array($transaksi->status_pesanan, ['Menunggu ACC', 'Dimasak', 'Siap Diantar', 'Diantar']) && ! $isCancelled;
@endphp

<section class="relative overflow-hidden bg-charcoal-950 py-10 sm:py-14 border-b border-charcoal-800/80">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Pesanan seafood Niskala Samudra" class="h-full w-full object-cover opacity-60">
        <div class="absolute inset-0 bg-charcoal-950/70"></div>
    </div>
    <div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('front.pesanan') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm text-slate-300 hover:text-gold-400 transition font-medium">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali ke Pesanan</span>
            </a>

            @if($isActiveOrder)
                <div x-data="{
                    countdown: 25,
                    timer: null,
                    init() {
                        this.timer = setInterval(() => {
                            if (this.countdown > 1) {
                                this.countdown--;
                            } else {
                                window.location.reload();
                            }
                        }, 1000);
                        this.$cleanup(() => {
                            if (this.timer) clearInterval(this.timer);
                        });
                    }
                }" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold backdrop-blur-md self-start sm:self-auto">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Live Tracking · Pembaruan <strong x-text="countdown" class="font-mono tabular-nums">25</strong>s</span>
                </div>
            @endif
        </div>

        <span class="text-xs font-semibold uppercase tracking-wider text-gold-400 block mt-4 sm:mt-5">Detail & Pelacakan Pesanan</span>
        <h1 class="mt-1 font-serif text-xl sm:text-3xl text-white font-bold break-all sm:break-normal">{{ $transaksi->kode_transaksi }}</h1>
        <p class="mt-1.5 text-xs sm:text-sm text-slate-300">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB · Layanan: <strong>{{ $transaksi->tipe_pesanan }}</strong></p>
    </div>
</section>

<section class="min-h-[60vh] bg-slate-50/70 py-10 sm:py-14">
    <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50 p-4 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-2xl border border-red-200/80 bg-red-50 p-4 text-red-800 text-xs sm:text-sm font-medium flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-base shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Visual Stepper Progress Bar -->
        @if($isCancelled)
            <div class="rounded-2xl border border-red-200/80 bg-red-50/80 p-5 sm:p-6 shadow-xs flex items-center gap-4 text-red-700">
                <div class="w-11 h-11 rounded-xl bg-red-100 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base">Pesanan Telah Dibatalkan</h3>
                    <p class="text-xs text-red-600 mt-0.5 leading-relaxed">Pesanan ini telah dibatalkan oleh pihak restoran atau sistem (melebihi batas waktu pembayaran). Hubungi kami jika terdapat kendala.</p>
                </div>
            </div>
        @else
            <div class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-xs">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="font-serif text-base sm:text-lg font-bold text-charcoal-950">Status Alur Pesanan</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Pantau tahapan proses pesanan Anda secara real-time.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold
                        {{ $transaksi->status_pesanan === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/70' : 'bg-gold-50 text-gold-800 border border-gold-200/70' }}">
                        <i class="fa-solid fa-circle text-[7px] {{ $transaksi->status_pesanan === 'Selesai' ? 'text-emerald-500' : 'text-gold-500' }}"></i>
                        {{ $transaksi->status_pesanan }}
                    </span>
                </div>

                <div class="relative">
                    @if($isDelivery)
                        <!-- Progress Line Delivery (5 steps) -->
                        <div class="hidden sm:block absolute top-1/2 left-0 right-0 h-0.5 bg-slate-200 -translate-y-1/2 -z-0">
                            <div class="h-full bg-gold-500 transition-all duration-700" style="width: {{ (($currentStep - 1) / 4) * 100 }}%"></div>
                        </div>

                        <!-- 5 Steps Grid for Delivery -->
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-2 relative z-10">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 1 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 1 ? 'bg-emerald-500 text-white' : ($currentStep === 1 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 1 ? 'fa-check' : 'fa-receipt' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">1. Diterima</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Menunggu ACC</span>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 2 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 2 ? 'bg-emerald-500 text-white' : ($currentStep === 2 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 2 ? 'fa-check' : 'fa-fire-burner' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">2. Dapur</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Sedang Dimasak</span>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 3 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 3 ? 'bg-emerald-500 text-white' : ($currentStep === 3 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 3 ? 'fa-check' : 'fa-boxes-packing' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">3. Dikemas</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Siap Diantar</span>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 4 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 4 ? 'bg-emerald-500 text-white' : ($currentStep === 4 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 4 ? 'fa-check' : 'fa-motorcycle' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">4. Perjalanan</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Menuju Lokasi</span>
                            </div>

                            <!-- Step 5 -->
                            <div class="col-span-2 sm:col-span-1 flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 5 ? 'bg-emerald-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep >= 5 ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">5. Selesai</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Pesanan Diterima</span>
                            </div>
                        </div>
                    @else
                        <!-- Progress Line Dine In (3 steps) -->
                        <div class="hidden sm:block absolute top-1/2 left-0 right-0 h-0.5 bg-slate-200 -translate-y-1/2 -z-0">
                            <div class="h-full bg-gold-500 transition-all duration-700" style="width: {{ (($currentStep - 1) / 2) * 100 }}%"></div>
                        </div>

                        <!-- 3 Steps Grid for Dine In -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 relative z-10">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 1 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 1 ? 'bg-emerald-500 text-white' : ($currentStep === 1 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 1 ? 'fa-check' : 'fa-receipt' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">1. Diterima</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Menunggu ACC Kasir</span>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 2 ? 'bg-gold-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep > 2 ? 'bg-emerald-500 text-white' : ($currentStep === 2 ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400') }}">
                                    <i class="fa-solid {{ $currentStep > 2 ? 'fa-check' : 'fa-fire-burner' }}"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">2. Dapur</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Sedang Dimasak</span>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col items-center text-center p-2.5 rounded-xl transition {{ $currentStep >= 3 ? 'bg-emerald-50/40 sm:bg-transparent' : 'opacity-50' }}">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center text-sm font-bold shadow-xs mb-2 transition-all
                                    {{ $currentStep >= 3 ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fa-solid fa-bell-concierge"></i>
                                </div>
                                <span class="text-xs font-semibold text-charcoal-950">3. Siap Saji</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">Dihidangkan ke Meja</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($isDelivery)
            <!-- Detail Pelacakan Kurir & Pengantaran (Khusus Layanan Delivery) -->
            <div x-data="{ showPod: false }" class="rounded-2xl sm:rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-7 shadow-xs overflow-hidden relative">
                <!-- Header Box -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gold-50 border border-gold-200 text-gold-700 flex items-center justify-center text-base shrink-0">
                            <i class="fa-solid fa-truck-ramp-box"></i>
                        </div>
                        <div>
                            <h2 class="font-serif text-base sm:text-lg font-bold text-charcoal-950">Pelacakan Pengantaran</h2>
                            <p class="text-xs text-slate-500 font-normal">Informasi kurir, waktu serah terima, dan bukti pengantaran pesanan.</p>
                        </div>
                    </div>

                    @if($transaksi->status_pesanan === 'Diantar')
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold self-start sm:self-auto">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </span>
                            <span>Kurir Sedang di Jalan</span>
                        </div>
                    @elseif($transaksi->status_pesanan === 'Selesai')
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold self-start sm:self-auto">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>Telah Diterima Pelanggan</span>
                        </div>
                    @elseif($transaksi->status_pesanan === 'Siap Diantar')
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-50 border border-sky-200 text-sky-800 text-xs font-semibold self-start sm:self-auto">
                            <i class="fa-solid fa-boxes-packing text-sky-600"></i>
                            <span>Dikemas - Siap Diambil Kurir</span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-5">
                    <!-- Kurir -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-motorcycle text-gold-500"></i> Kurir Pengantar
                            </p>
                            <p class="text-sm font-semibold text-charcoal-950 mt-1">
                                {{ $transaksi->kurir->nama ?? 'Menunggu Penugasan' }}
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ $transaksi->kurir ? 'Staff Kurir Niskala Samudra' : 'Akan ditugaskan saat siap antar' }}
                            </p>
                        </div>
                    </div>

                    <!-- Waktu Berangkat -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1 flex items-center gap-1.5">
                                <i class="fa-regular fa-clock text-gold-500"></i> Waktu Berangkat
                            </p>
                            <p class="text-sm font-semibold text-charcoal-950 mt-1">
                                {{ $transaksi->waktu_berangkat ? $transaksi->waktu_berangkat->format('H:i') . ' WIB' : 'Belum Berangkat' }}
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ $transaksi->waktu_berangkat ? $transaksi->waktu_berangkat->format('d M Y') : 'Menunggu kurir berangkat' }}
                            </p>
                        </div>
                    </div>

                    <!-- Waktu Tiba / Estimasi -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-hourglass-half text-gold-500"></i> {{ $transaksi->status_pesanan === 'Selesai' ? 'Waktu Tiba' : 'Estimasi Tiba' }}
                            </p>
                            <p class="text-sm font-semibold text-charcoal-950 mt-1">
                                @if($transaksi->waktu_sampai)
                                    Sampai pk {{ $transaksi->waktu_sampai->format('H:i') }} WIB
                                @elseif($transaksi->status_pesanan === 'Diantar')
                                    {{ $transaksi->estimasi_sampai }}
                                @else
                                    25 - 35 Menit
                                @endif
                            </p>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                @if($transaksi->status_pesanan === 'Selesai' && $transaksi->durasi_pengantaran)
                                    <span class="text-emerald-700 font-semibold"><i class="fa-solid fa-check mr-1"></i> Durasi: {{ $transaksi->durasi_pengantaran }}</span>
                                @elseif($transaksi->status_pesanan === 'Diantar')
                                    <span class="text-amber-700 font-semibold"><i class="fa-solid fa-route mr-1"></i> {{ $transaksi->durasi_pengantaran ?? 'Sedang di perjalanan' }}</span>
                                @else
                                    Dihitung saat kurir berangkat
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Penerima & Bukti Pengantaran -->
                    <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-1 flex items-center gap-1.5">
                                <i class="fa-solid fa-house-chimney-user text-gold-500"></i> Penerima
                            </p>
                            <p class="text-sm font-semibold text-charcoal-950 mt-1 truncate">
                                {{ $transaksi->diterima_oleh ?? ($transaksi->status_pesanan === 'Selesai' ? ($transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan) : 'Dalam Proses') }}
                            </p>
                            @if($transaksi->bukti_pengantaran)
                                <div class="mt-2 flex items-center gap-2">
                                    <button type="button" @click="showPod = true" class="relative group block w-9 h-9 rounded-lg overflow-hidden border border-slate-200 shadow-2xs shrink-0 cursor-pointer">
                                        <img src="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" alt="Bukti Serah Terima" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </button>
                                    <button type="button" @click="showPod = true" class="text-[11px] font-semibold text-gold-700 hover:text-gold-800 underline text-left leading-tight">
                                        Foto Bukti Serah Terima
                                    </button>
                                </div>
                            @else
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $transaksi->status_pesanan === 'Selesai' ? 'Diserahkan langsung' : 'Foto saat tiba di lokasi' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($transaksi->status_pesanan === 'Diantar')
                    <div class="mt-4 p-3.5 rounded-xl bg-amber-50/70 border border-amber-200/80 flex items-start gap-2.5 text-amber-900 text-xs">
                        <i class="fa-solid fa-route text-amber-600 text-sm mt-0.5 shrink-0"></i>
                        <div class="leading-relaxed">
                            <span class="font-bold">Kurir Sedang Mengantar:</span> Berangkat pada pukul <strong>{{ $transaksi->waktu_berangkat ? $transaksi->waktu_berangkat->format('H:i') . ' WIB' : '-' }}</strong> dengan estimasi tiba di lokasi Anda <strong>{{ $transaksi->estimasi_sampai }}</strong>. Pastikan nomor telepon Anda (<strong>{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan }}</strong>) dapat dihubungi.
                        </div>
                    </div>
                @elseif($transaksi->status_pesanan === 'Selesai')
                    <div class="mt-4 p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-start gap-2.5 text-emerald-900 text-xs">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-sm mt-0.5 shrink-0"></i>
                        <div class="leading-relaxed">
                            <span class="font-bold">Pesanan Telah Tiba:</span> Makanan telah diserahkan kepada <strong>{{ $transaksi->diterima_oleh ?? ($transaksi->pelanggan->nama ?? 'Pelanggan') }}</strong> pada pukul <strong>{{ $transaksi->waktu_sampai ? $transaksi->waktu_sampai->format('H:i') . ' WIB' : '-' }}</strong> (Total waktu antar: <strong>{{ $transaksi->durasi_pengantaran ?? 'Tepat Waktu' }}</strong>). Selamat menikmati sajian dari Niskala Samudra!
                        </div>
                    </div>
                @endif

                <!-- Lightbox Modal untuk Bukti Pengantaran -->
                @if($transaksi->bukti_pengantaran)
                    <div x-show="showPod" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-charcoal-950/80 backdrop-blur-sm flex items-center justify-center p-4" x-transition.opacity>
                        <div @click.away="showPod = false" class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl border border-slate-200">
                            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="font-bold text-charcoal-900 text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-camera text-gold-500"></i> Bukti Serah Terima Pesanan
                                </h3>
                                <button type="button" @click="showPod = false" class="text-slate-400 hover:text-charcoal-900 text-sm p-1 rounded-lg">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="p-4 bg-slate-50 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" alt="Foto Bukti Pengantaran" class="max-h-[70vh] rounded-xl object-contain shadow-sm">
                            </div>
                            <div class="p-4 bg-white border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                                <span>Penerima: <strong>{{ $transaksi->diterima_oleh ?? '-' }}</strong></span>
                                <span>Waktu: {{ $transaksi->waktu_sampai ? $transaksi->waktu_sampai->format('d M Y, H:i') : '-' }} WIB</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Status Pesanan</p>
                <p class="mt-1.5 font-semibold text-charcoal-950 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-utensils text-gold-500"></i> {{ $transaksi->status_pesanan }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Pembayaran</p>
                <p class="mt-1.5 font-semibold text-sm sm:text-base {{ $transaksi->status_pembayaran === 'Lunas' ? 'text-emerald-600' : ($isCancelled ? 'text-red-600' : 'text-amber-600') }} flex items-center gap-2">
                    <i class="fa-solid {{ $transaksi->status_pembayaran === 'Lunas' ? 'fa-circle-check' : ($isCancelled ? 'fa-circle-xmark' : 'fa-clock') }}"></i>
                    {{ $transaksi->status_pembayaran }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 sm:p-5 shadow-xs">
                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-semibold">Metode Bayar</p>
                <p class="mt-1.5 font-semibold text-charcoal-950 text-sm sm:text-base flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-gold-500"></i> {{ $transaksi->metode_pembayaran === 'QRIS' ? 'QRIS / Transfer' : $transaksi->metode_pembayaran }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <h2 class="font-serif text-base font-bold text-charcoal-950 border-b border-slate-100 pb-3">Informasi Pesanan</h2>
                <div class="mt-4 space-y-3.5 text-xs sm:text-sm">
                    <div><p class="text-[11px] text-slate-400 font-medium">Nama Pemesan</p><p class="mt-0.5 font-semibold text-charcoal-950">{{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan }}</p></div>
                    <div><p class="text-[11px] text-slate-400 font-medium">Nomor HP</p><p class="mt-0.5 text-charcoal-900">{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan ?? 'Belum tersedia' }}</p></div>
                    @if($transaksi->alamat)
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium">Alamat Pengiriman</p>
                            <p class="mt-0.5 leading-relaxed text-charcoal-900">{{ $transaksi->alamat }}</p>
                            @if($transaksi->patokan_alamat)
                                <p class="mt-1.5 text-xs text-slate-500 flex items-center gap-1.5 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    <i class="fa-solid fa-map-pin text-gold-500 shrink-0"></i>
                                    <span><strong>Patokan:</strong> {{ $transaksi->patokan_alamat }}</span>
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-xs">
                <div class="border-b border-slate-100 px-5 py-3.5"><h2 class="font-serif text-base font-bold text-charcoal-950">Rincian Menu Dipesan</h2></div>
                <div class="divide-y divide-slate-100">
                    @foreach($transaksi->detail as $detail)
                        <div class="flex items-center justify-between gap-4 px-5 py-3.5">
                            <div><p class="font-semibold text-charcoal-950 text-xs sm:text-sm">{{ $detail->menu->nama_menu ?? $detail->nama_menu }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $detail->qty }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p></div>
                            <p class="font-semibold text-gold-600 tabular-nums text-xs sm:text-sm">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-2 border-t border-slate-200/80 bg-slate-50/70 px-5 py-3.5 text-xs sm:text-sm">
                    <div class="flex justify-between text-slate-500"><span>Subtotal</span><span class="font-semibold text-charcoal-900 tabular-nums">Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</span></div>
                    @if($transaksi->ongkos_kirim > 0)<div class="flex justify-between text-slate-500"><span>Ongkos kirim</span><span class="font-semibold text-charcoal-900 tabular-nums">Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</span></div>@endif
                    <div class="flex justify-between items-baseline border-t border-slate-200 pt-2 font-bold text-sm sm:text-base"><span>Total</span><span class="text-gold-600 font-serif text-base sm:text-lg tabular-nums">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>

        @if($transaksi->catatan)
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs"><h2 class="font-serif font-bold text-sm text-charcoal-950 border-b border-slate-100 pb-2">Catatan Pesanan</h2><p class="mt-2 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-slate-600">{{ $transaksi->catatan }}</p></div>
        @endif

        <div class="flex flex-col sm:flex-row flex-wrap gap-2.5">
            <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200/80 bg-white px-5 py-2.5 text-xs sm:text-sm font-semibold text-charcoal-950 hover:bg-slate-50 transition shadow-xs"><i class="fa-solid fa-receipt"></i> Lihat Struk</a>
            @if($transaksi->metode_pembayaran === 'QRIS' && $transaksi->status_pembayaran === 'Belum Lunas' && !$isCancelled && $transaksi->status_pesanan !== 'Selesai')
                <a href="{{ URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gold-500 px-5 py-2.5 text-xs sm:text-sm font-semibold text-charcoal-950 hover:bg-gold-600 hover:text-white transition shadow-xs"><i class="fa-solid fa-wallet"></i> Lanjutkan Pembayaran</a>
            @endif
            @if($transaksi->status_pesanan === 'Menunggu ACC' && $transaksi->status_pembayaran === 'Belum Lunas' && !$isCancelled)
                <form action="{{ route('front.pesanan.batal', $transaksi->kode_transaksi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 px-5 py-2.5 text-xs sm:text-sm font-semibold transition shadow-xs">
                        <i class="fa-solid fa-ban"></i> Batalkan Pesanan
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
