@extends('template.front')

@section('content')
<section class="bg-slate-50 py-16 sm:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 w-full">
        <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-12 text-center border border-slate-100">

            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center text-3xl sm:text-4xl mx-auto mb-4 sm:mb-6">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1 class="text-2xl sm:text-3xl font-serif text-charcoal-900 mb-1.5 sm:mb-2">Pesanan Berhasil!</h1>
            <p class="text-slate-500 text-xs sm:text-sm mb-6 sm:mb-8">Terima kasih, pesanan Anda sedang kami proses. Mohon tunggu informasi selanjutnya.</p>

            <div class="bg-slate-50 rounded-2xl p-5 sm:p-6 border border-slate-200 mb-8 text-left">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 text-sm">
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Kode Pesanan</p>
                        <p class="font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Total Pembayaran</p>
                        <p class="font-bold text-gold-600">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Status Pembayaran</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->status_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Tipe Pesanan</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->tipe_pesanan }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Metode Pembayaran</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->metode_pembayaran }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 justify-center">
                @auth
                    <a href="{{ route('front.pesanan.detail', $transaksi->kode_transaksi) }}" class="w-full sm:w-auto px-6 sm:px-7 py-3 bg-gold-600 text-white font-bold rounded-xl hover:bg-gold-500 transition uppercase tracking-wider text-xs sm:text-sm shadow-lg shadow-gold-600/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i> Pantau Status Pesanan
                    </a>
                @endauth
                <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="w-full sm:w-auto px-6 sm:px-7 py-3 bg-white text-charcoal-900 font-bold rounded-xl border border-charcoal-900 hover:bg-slate-50 transition uppercase tracking-wider text-xs sm:text-sm shadow-xs flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                <a href="{{ route('menu') }}" class="w-full sm:w-auto px-6 sm:px-7 py-3 bg-charcoal-900 text-white font-bold rounded-xl hover:bg-gold-600 transition uppercase tracking-wider text-xs sm:text-sm shadow-xs flex items-center justify-center gap-2">
                    Pesan Lagi <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</section>

<script>
    localStorage.removeItem('niskala_cart');
</script>
@endsection
