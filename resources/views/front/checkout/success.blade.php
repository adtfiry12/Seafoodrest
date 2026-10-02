@extends('template.front')

@section('content')
<section class="bg-slate-50/70 py-12 sm:py-16 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 w-full">
        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs p-7 sm:p-10 text-center border border-slate-200/80">

            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mx-auto mb-4">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-charcoal-950 mb-1.5">Pesanan Berhasil Dibuat!</h1>
            <p class="text-slate-500 text-xs sm:text-sm mb-6 max-w-md mx-auto leading-relaxed">Terima kasih atas kepercayaan Anda. Pesanan sedang kami proses dan segera disiapkan oleh staf kami.</p>

            <div class="bg-slate-50/70 rounded-xl p-5 border border-slate-200/80 mb-7 text-left">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-sm">
                    <div>
                        <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-0.5">Kode Pesanan</p>
                        <p class="font-mono font-bold text-charcoal-950">{{ $transaksi->kode_transaksi }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-0.5">Total Pembayaran</p>
                        <p class="font-bold text-gold-600 tabular-nums">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-0.5">Status Pembayaran</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->status_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-0.5">Tipe Pesanan</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->tipe_pesanan }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider mb-0.5">Metode Pembayaran</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->metode_pembayaran }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row flex-wrap gap-2.5 sm:gap-3 justify-center text-xs sm:text-sm">
                @auth
                    <a href="{{ route('front.pesanan.detail', $transaksi->kode_transaksi) }}" class="w-full sm:w-auto px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Pantau Pesanan</span>
                    </a>
                @endauth
                <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="w-full sm:w-auto px-5 py-2.5 bg-white text-charcoal-900 font-semibold rounded-xl border border-slate-200 hover:bg-slate-50 transition shadow-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Struk</span>
                </a>
                <a href="{{ route('menu') }}" class="w-full sm:w-auto px-5 py-2.5 bg-charcoal-900 text-white font-semibold rounded-xl hover:bg-gold-600 transition shadow-xs flex items-center justify-center gap-2">
                    <span>Pesan Lagi</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </div>
</section>

<script>
    localStorage.removeItem('niskala_cart');
</script>
@endsection
