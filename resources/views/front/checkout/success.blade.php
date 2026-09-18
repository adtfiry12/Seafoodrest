@extends('template.front')

@section('content')
<section class="bg-slate-50 py-16 sm:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-2xl mx-auto px-4 w-full">
        <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-12 text-center border border-slate-100">

            <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center text-5xl mx-auto mb-6">
                <i class="fa-solid fa-check"></i>
            </div>

            <h1 class="text-3xl font-serif text-charcoal-900 mb-2">Pesanan Berhasil!</h1>
            <p class="text-slate-500 mb-8">Terima kasih, pesanan Anda sedang kami proses. Mohon tunggu informasi selanjutnya.</p>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 mb-8 text-left">
                <div class="grid grid-cols-2 gap-4 text-sm">
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
                    <div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-wider mb-1">Metode Pembayaran</p>
                        <p class="font-medium text-charcoal-900">{{ $transaksi->metode_pembayaran }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="px-8 py-3 bg-white text-charcoal-900 font-bold rounded-xl border border-charcoal-900 hover:bg-slate-50 transition uppercase tracking-wider text-sm shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak Struk
                </a>
                <a href="{{ route('menu') }}" class="px-8 py-3 bg-charcoal-900 text-white font-bold rounded-xl hover:bg-gold-600 transition uppercase tracking-wider text-sm shadow-md flex items-center justify-center gap-2">
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
