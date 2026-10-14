@extends('template.front')

@section('content')
@php
    $isCancelled = in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal'], true) || $transaksi->status_pembayaran === 'Batal';
    $isExpired = $transaksi->isQrisExpired();
    $isLunas = $transaksi->status_pembayaran === 'Lunas';
    $statusPollUrl = route('checkout.qris.status', ['kode_transaksi' => $transaksi->kode_transaksi]);
    $suksesUrl = URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]);
    $strukUrl = URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]);
@endphp

<script type="text/javascript" src="{{ $midtransSnapJsUrl }}" data-client-key="{{ $midtransClientKey }}"></script>

<div class="bg-charcoal-950 py-8 sm:py-10 border-b border-charcoal-800/80">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gold-400">Pembayaran Pesanan</span>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white mt-1">Selesaikan Pembayaran</h1>
                <p class="text-slate-400 text-xs sm:text-sm mt-1">
                    Silakan klik tombol bayar di bawah untuk menyelesaikan transaksi Anda.
                </p>
            </div>
            <div class="text-left sm:text-right bg-charcoal-900/90 border border-charcoal-800 rounded-2xl px-4 py-3">
                <p class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">No. Pesanan</p>
                <p class="font-mono font-bold text-gold-400 text-sm sm:text-base">{{ $transaksi->kode_transaksi }}</p>
            </div>
        </div>
    </div>
</div>

<section class="bg-slate-50/80 py-8 sm:py-12 min-h-[70vh]"
         x-data="paymentPage({
             isLunas: {{ $isLunas ? 'true' : 'false' }},
             isCancelled: {{ ($isCancelled || $isExpired) ? 'true' : 'false' }},
             statusPembayaran: @js($transaksi->status_pembayaran),
             statusPesanan: @js($transaksi->status_pesanan),
             paymentChannel: @js($transaksi->payment_channel_label),
             paymentReference: @js($transaksi->payment_reference ?? '-'),
             dibayarPada: @js($transaksi->dibayar_pada ? $transaksi->dibayar_pada->format('d M Y, H:i').' WIB' : '-'),
             seconds: {{ (int) $transaksi->sisa_detik_qris }},
             snapToken: @js($snapToken ?? ''),
             statusPollUrl: @js($statusPollUrl),
             suksesUrl: @js($suksesUrl),
         })"
         x-init="initTracker()">

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Step Progress -->
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/80 p-5 sm:p-6 shadow-xs">
            <div class="grid grid-cols-3 gap-2 sm:gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 text-center sm:text-left">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Langkah 1</p>
                        <p class="text-xs sm:text-sm font-bold text-charcoal-950">Pesanan Dibuat</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 text-center sm:text-left">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition-colors"
                         :class="isLunas ? 'bg-emerald-500 text-white' : (isCancelled ? 'bg-rose-500 text-white' : 'bg-gold-500 text-charcoal-950')">
                        <template x-if="isLunas"><i class="fa-solid fa-check"></i></template>
                        <template x-if="isCancelled && !isLunas"><i class="fa-solid fa-xmark"></i></template>
                        <template x-if="!isLunas && !isCancelled"><i class="fa-solid fa-wallet"></i></template>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider"
                           :class="isLunas ? 'text-emerald-700' : (isCancelled ? 'text-rose-600' : 'text-gold-700')">Langkah 2</p>
                        <p class="text-xs sm:text-sm font-bold text-charcoal-950"
                           x-text="isLunas ? 'Pembayaran Lunas' : (isCancelled ? 'Dibatalkan' : 'Pembayaran')"></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3 text-center sm:text-left">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 transition-colors"
                         :class="isLunas ? 'bg-gold-500 text-charcoal-950' : 'bg-slate-100 text-slate-400 border border-slate-200'">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider"
                           :class="isLunas ? 'text-gold-700' : 'text-slate-400'">Langkah 3</p>
                        <p class="text-xs sm:text-sm font-bold"
                           :class="isLunas ? 'text-charcoal-950' : 'text-slate-400'"
                           x-text="statusPesanan === 'Menunggu ACC' ? 'Diproses Resto' : statusPesanan"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification (Hanya untuk peringatan error/gagal) -->
        <div x-show="notificationMessage && notificationType === 'error'" x-transition x-cloak
             class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm font-medium text-rose-800 flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                <span x-text="notificationMessage"></span>
            </div>
            <button type="button" @click="notificationMessage = ''" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs overflow-hidden border border-slate-200/80">

            <!-- Header Total Tagihan -->
            <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                 :class="isLunas ? 'bg-emerald-50/50' : (isCancelled ? 'bg-rose-50/40' : 'bg-gold-500/10')">
                <div>
                    <div class="flex flex-wrap items-center gap-2.5 mb-1.5">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Tagihan</span>
                        <span x-show="isLunas" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider">
                            <i class="fa-solid fa-check"></i> Lunas
                        </span>
                        <span x-show="!isLunas && !isCancelled" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200 text-[11px] font-bold uppercase tracking-wider">
                            Menunggu Pembayaran
                        </span>
                        <span x-show="isCancelled && !isLunas" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-600 text-white text-[11px] font-bold uppercase tracking-wider">
                            Dibatalkan
                        </span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold font-serif text-charcoal-950 tabular-nums">
                        Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}
                    </h2>
                    <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-slate-500">
                        <span>Subtotal: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</strong></span>
                        @if($transaksi->ongkos_kirim > 0)
                            <span>Ongkir: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</strong></span>
                        @endif
                        <span>Layanan: <strong class="text-charcoal-900">{{ $transaksi->tipe_pesanan }}</strong></span>
                    </div>
                </div>

                <!-- Countdown -->
                <div x-show="!isLunas && !isCancelled" x-cloak
                     class="flex items-center gap-3 bg-charcoal-950 text-white px-4 py-3 rounded-2xl border border-charcoal-800 shadow-sm shrink-0 self-start sm:self-center">
                    <i class="fa-regular fa-clock text-gold-400 text-base"></i>
                    <div>
                        <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider leading-none">Batas Waktu</p>
                        <p x-text="formatTime(seconds)" class="font-mono font-bold text-base sm:text-lg tabular-nums text-gold-400 leading-tight mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- KONDISI 1: SUDAH LUNAS -->
                <div x-show="isLunas" x-transition x-cloak class="space-y-6">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-6 sm:p-8 text-center space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl mx-auto shadow-sm">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <h3 class="font-serif text-xl sm:text-2xl font-bold text-charcoal-950">Pembayaran Berhasil</h3>
                            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-md mx-auto leading-relaxed">
                                Terima kasih, pembayaran Anda telah diterima dan pesanan segera diproses oleh restoran kami.
                            </p>
                        </div>

                        <div class="max-w-md mx-auto bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 text-left space-y-2.5 text-xs sm:text-sm">
                            <div class="flex justify-between items-center pb-2.5 border-b border-slate-100">
                                <span class="text-slate-500">Status Pembayaran</span>
                                <span class="font-bold text-emerald-700">Lunas</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Metode Pembayaran</span>
                                <strong class="text-charcoal-950" x-text="paymentChannel"></strong>
                            </div>
                            <div class="flex justify-between items-center" x-show="paymentReference && paymentReference !== '-'">
                                <span class="text-slate-500">No. Referensi</span>
                                <span class="font-mono font-semibold text-charcoal-900" x-text="paymentReference"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">Waktu Pembayaran</span>
                                <span class="font-medium text-charcoal-900" x-text="dibayarPada"></span>
                            </div>
                            <div class="flex justify-between items-center pt-2.5 border-t border-slate-100">
                                <span class="text-slate-500">Status Pesanan</span>
                                <span class="font-bold text-charcoal-950" x-text="statusPesanan"></span>
                            </div>
                        </div>

                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a :href="suksesUrl" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-charcoal-950 text-white hover:bg-gold-500 hover:text-charcoal-950 text-xs sm:text-sm font-semibold rounded-xl transition">
                                <span>Lihat Detail Pesanan</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </a>
                            <a href="{{ $strukUrl }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 text-charcoal-900 hover:bg-slate-50 text-xs sm:text-sm font-semibold rounded-xl transition">
                                <i class="fa-solid fa-print"></i>
                                <span>Cetak Struk</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KONDISI 2: PESANAN BATAL / KEDALUWARSA -->
                <div x-show="isCancelled && !isLunas" x-transition x-cloak>
                    <div class="rounded-2xl border border-rose-200/80 bg-rose-50/80 p-6 sm:p-8 text-center space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto border border-rose-200">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <h3 class="font-serif text-lg sm:text-xl font-bold text-rose-950">Waktu Pembayaran Berakhir</h3>
                            <p class="text-xs sm:text-sm text-rose-700 mt-1.5 max-w-md mx-auto leading-relaxed">
                                Pesanan ini telah dibatalkan karena melewati batas waktu pembayaran. Silakan buat pesanan baru.
                            </p>
                        </div>
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ route('menu') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-charcoal-950 text-white hover:bg-gold-600 text-xs sm:text-sm font-semibold rounded-xl transition">
                                <i class="fa-solid fa-utensils text-xs"></i>
                                <span>Kembali ke Menu</span>
                            </a>
                            @auth
                                <a href="{{ route('front.pesanan') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-white border border-slate-200 text-charcoal-900 hover:bg-slate-50 text-xs sm:text-sm font-semibold rounded-xl transition">
                                    <span>Riwayat Pesanan</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- KONDISI 3: MENUNGGU PEMBAYARAN -->
                <div x-show="!isLunas && !isCancelled" x-transition x-cloak class="space-y-6">

                    <div class="rounded-2xl border border-slate-200/90 bg-slate-50/60 p-5 sm:p-6 space-y-4">

                        <!-- Nomor VA / Kode Pembayaran jika sudah dipilih -->
                        <div x-show="paymentReference && paymentReference !== '-'" x-cloak
                             class="bg-emerald-50/70 border-2 border-emerald-500/40 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider" x-text="paymentChannel"></p>
                                </div>
                                <p class="font-mono font-extrabold text-xl sm:text-2xl text-charcoal-950 tracking-wider select-all" x-text="paymentReference"></p>
                                <p class="text-[11px] text-slate-500">Salin dan gunakan nomor di atas untuk menyelesaikan transfer pembayaran Anda.</p>
                            </div>
                            <button type="button"
                                    @click="navigator.clipboard.writeText(paymentReference); notificationType = 'success'; notificationMessage = 'Nomor pembayaran ' + paymentReference + ' berhasil disalin!';"
                                    class="w-full sm:w-auto px-4 py-2.5 bg-charcoal-950 hover:bg-gold-500 text-white hover:text-charcoal-950 rounded-xl text-xs font-bold transition shrink-0 flex items-center justify-center gap-2 cursor-pointer shadow-xs active:scale-95">
                                <i class="fa-regular fa-copy"></i>
                                <span>Salin Nomor</span>
                            </button>
                        </div>

                        <!-- Action Button Utama -->
                        <div class="pt-1">
                            <button type="button"
                                    @click="openPaymentPopup()"
                                    class="w-full py-3.5 px-6 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white font-bold text-sm rounded-xl transition shadow-xs flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
                                <i class="fa-solid fa-wallet"></i>
                                <span>Bayar Sekarang</span>
                            </button>
                        </div>
                    </div>

                    <!-- Rincian Pesanan -->
                    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Rincian Pesanan</h4>
                            <span class="text-xs font-semibold text-charcoal-900">{{ $transaksi->detail->sum('qty') }} Porsi</span>
                        </div>
                        <div class="divide-y divide-slate-100 text-xs sm:text-sm">
                            @foreach($transaksi->detail as $dt)
                                <div class="py-2.5 flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-charcoal-900">{{ $dt->menu->nama_menu ?? $dt->nama_menu }}</p>
                                        <p class="text-xs text-slate-500">{{ $dt->qty }} x Rp {{ number_format($dt->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-bold text-charcoal-900 tabular-nums">Rp {{ number_format($dt->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</section>

<script>
    localStorage.removeItem('niskala_cart');

    function paymentPage(config) {
        return {
            isLunas: config.isLunas,
            isCancelled: config.isCancelled,
            statusPembayaran: config.statusPembayaran,
            statusPesanan: config.statusPesanan,
            paymentChannel: config.paymentChannel,
            paymentReference: config.paymentReference,
            dibayarPada: config.dibayarPada,
            seconds: config.seconds,
            snapToken: config.snapToken,
            statusPollUrl: config.statusPollUrl,
            suksesUrl: config.suksesUrl,

            selectedChannelHint: @js($transaksi->payment_type ?? ''),
            isChecking: false,
            lastOrderIdHint: null,
            notificationMessage: '',
            notificationType: 'success',
            pollInterval: null,
            countdownInterval: null,

            initTracker() {
                if (!this.isLunas && !this.isCancelled) {
                    this.startCountdown();
                    this.startSilentWatcher();

                    window.addEventListener('storage', (e) => {
                        if (e.key === 'niskala_payment_settled' && e.newValue === @js($transaksi->kode_transaksi)) {
                            this.isLunas = true;
                            this.cleanupSnapModal();
                            window.location.href = this.suksesUrl;
                        }
                    });

                    try {
                        const channel = new BroadcastChannel('niskala_checkout');
                        channel.onmessage = (e) => {
                            if (e.data?.status === 'Lunas' && e.data?.kode === @js($transaksi->kode_transaksi)) {
                                this.isLunas = true;
                                this.cleanupSnapModal();
                                window.location.href = this.suksesUrl;
                            }
                        };
                    } catch (err) {}
                }
            },

            cleanupSnapModal() {
                try {
                    const snapIframe = document.getElementById('snap-midtrans') || document.querySelector('iframe[src*="midtrans"]');
                    if (snapIframe) snapIframe.remove();
                    const snapContainer = document.getElementById('snap-container');
                    if (snapContainer) snapContainer.remove();
                } catch (e) {}
            },

            startCountdown() {
                this.countdownInterval = setInterval(() => {
                    if (this.isLunas || this.isCancelled) {
                        clearInterval(this.countdownInterval);
                        return;
                    }
                    if (this.seconds > 1) {
                        this.seconds--;
                    } else {
                        this.seconds = 0;
                        clearInterval(this.countdownInterval);
                        this.checkSilentStatus();
                    }
                }, 1000);
            },

            startSilentWatcher() {
                this.pollInterval = setInterval(() => {
                    if (this.isLunas || this.isCancelled) {
                        clearInterval(this.pollInterval);
                        return;
                    }
                    this.checkSilentStatus();
                }, 2000);

                window.addEventListener('focus', () => {
                    if (!this.isLunas && !this.isCancelled) {
                        this.isChecking = false;
                        this.checkSilentStatus();
                    }
                });

                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'visible' && !this.isLunas && !this.isCancelled) {
                        this.isChecking = false;
                        this.checkSilentStatus();
                    }
                });
            },

            formatTime(sec) {
                if (sec <= 0) return '00:00:00';
                const h = Math.floor(sec / 3600);
                const m = Math.floor((sec % 3600) / 60);
                const s = sec % 60;
                const pad = (n) => String(n).padStart(2, '0');
                return pad(h) + ':' + pad(m) + ':' + pad(s);
            },

            openPaymentPopup() {
                if (!this.snapToken || !window.snap) {
                    this.notificationType = 'error';
                    this.notificationMessage = 'Layanan pembayaran sedang dimuat, silakan coba sesaat lagi.';
                    return;
                }

                const self = this;
                window.snap.pay(this.snapToken, {
                    onSuccess: function(result) {
                        self.handleSnapResult(result);
                    },
                    onPending: function(result) {
                        self.handleSnapResult(result);
                    },
                    onError: function() {
                        self.notificationType = 'error';
                        self.notificationMessage = 'Pembayaran belum berhasil diproses. Silakan coba kembali.';
                    },
                    onClose: function() {
                        self.isChecking = false;
                        self.checkSilentStatus();
                    }
                });
            },

            handleSnapResult(result) {
                if (!result) {
                    this.isChecking = false;
                    this.checkSilentStatus();
                    return;
                }

                let extractedRef = null;
                let extractedChannel = null;

                if (result.va_numbers && result.va_numbers.length > 0) {
                    extractedRef = result.va_numbers[0].va_number;
                    const bank = (result.va_numbers[0].bank || '').toUpperCase();
                    extractedChannel = bank ? bank + ' Virtual Account' : 'Virtual Account';
                } else if (result.permata_va_number) {
                    extractedRef = result.permata_va_number;
                    extractedChannel = 'Permata Virtual Account';
                } else if (result.bill_key) {
                    const billerCode = result.biller_code ? result.biller_code + ' - ' : '';
                    extractedRef = billerCode + result.bill_key;
                    extractedChannel = 'Mandiri Virtual Account';
                } else if (result.payment_code) {
                    extractedRef = result.payment_code;
                    extractedChannel = (result.store || 'Gerai').toUpperCase();
                } else if (result.payment_type === 'gopay') {
                    extractedChannel = 'GoPay';
                } else if (result.payment_type === 'ovo') {
                    extractedChannel = 'OVO';
                } else if (result.payment_type === 'shopeepay') {
                    extractedChannel = 'ShopeePay';
                } else if (result.payment_type === 'qris') {
                    extractedChannel = 'QRIS';
                }

                if (extractedRef) {
                    this.paymentReference = extractedRef;
                }
                if (extractedChannel) {
                    this.paymentChannel = extractedChannel;
                }

                this.isChecking = false;
                this.checkSilentStatus(result);
            },

            async checkSilentStatus(callbackResult = null) {
                if (this.isChecking || this.isLunas || this.isCancelled) return;
                this.isChecking = true;

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 3500);

                try {
                    let url = new URL(this.statusPollUrl, window.location.origin);
                    if (callbackResult && typeof callbackResult === 'object') {
                        if (callbackResult.order_id) url.searchParams.set('order_id', callbackResult.order_id);
                        if (callbackResult.transaction_id) url.searchParams.set('transaction_id', callbackResult.transaction_id);
                        if (callbackResult.transaction_status) url.searchParams.set('transaction_status', callbackResult.transaction_status);
                        if (callbackResult.status_code) url.searchParams.set('status_code', callbackResult.status_code);
                        if (callbackResult.payment_type) url.searchParams.set('payment_type', callbackResult.payment_type);
                        if (callbackResult.issuer) url.searchParams.set('issuer', callbackResult.issuer);
                        if (callbackResult.acquirer) url.searchParams.set('acquirer', callbackResult.acquirer);

                        if (callbackResult.va_numbers && callbackResult.va_numbers[0]) {
                            url.searchParams.set('va_number', callbackResult.va_numbers[0].va_number);
                            url.searchParams.set('bank', callbackResult.va_numbers[0].bank || '');
                        } else if (callbackResult.permata_va_number) {
                            url.searchParams.set('permata_va_number', callbackResult.permata_va_number);
                        } else if (callbackResult.bill_key) {
                            url.searchParams.set('bill_key', callbackResult.bill_key);
                            if (callbackResult.biller_code) url.searchParams.set('biller_code', callbackResult.biller_code);
                        } else if (callbackResult.payment_code) {
                            url.searchParams.set('payment_code', callbackResult.payment_code);
                        }

                        this.lastOrderIdHint = callbackResult.order_id || this.lastOrderIdHint;
                    } else if (this.lastOrderIdHint) {
                        url.searchParams.set('order_id', this.lastOrderIdHint);
                    }

                    if (this.selectedChannelHint) {
                        url.searchParams.set('channel', this.selectedChannelHint);
                    } else if (this.paymentChannel && this.paymentChannel !== '-' && !url.searchParams.has('channel')) {
                        url.searchParams.set('channel', this.paymentChannel);
                    }

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        cache: 'no-store',
                        signal: controller.signal
                    });

                    clearTimeout(timeoutId);

                    if (response.ok) {
                        const data = await response.json();
                        this.statusPembayaran = data.status_pembayaran;
                        this.statusPesanan = data.status_pesanan;
                        if (data.payment_channel && data.payment_channel !== '-') {
                            this.paymentChannel = data.payment_channel;
                        }
                        if (data.payment_reference && data.payment_reference !== '-') {
                            this.paymentReference = data.payment_reference;
                        }
                        this.dibayarPada = data.dibayar_pada || this.dibayarPada;

                        if (data.is_lunas) {
                            this.isLunas = true;
                            if (this.pollInterval) clearInterval(this.pollInterval);
                            if (this.countdownInterval) clearInterval(this.countdownInterval);
                            this.cleanupSnapModal();
                            if (data.sukses_url) {
                                window.location.href = data.sukses_url;
                            } else {
                                window.location.reload();
                            }
                            return;
                        }

                        if (data.is_batal) {
                            this.isCancelled = true;
                            if (this.pollInterval) clearInterval(this.pollInterval);
                            if (this.countdownInterval) clearInterval(this.countdownInterval);
                            if (window.snap && typeof window.snap.hide === 'function') {
                                try { window.snap.hide(); } catch (e) {}
                            }
                            window.location.reload();
                            return;
                        }
                    }
                } catch (e) {
                    // Silent background check
                } finally {
                    clearTimeout(timeoutId);
                    this.isChecking = false;
                }
            }
        };
    }
</script>
@endsection
