@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-10 sm:py-12 border-b border-charcoal-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-semibold uppercase tracking-wider text-gold-400">Pembayaran Aman</span>
        <h1 class="text-2xl sm:text-4xl font-serif font-bold text-white mt-1 mb-2">Pembayaran QRIS / Transfer</h1>
        <p class="text-slate-400 text-sm font-normal max-w-md mx-auto">Selesaikan pembayaran agar pesanan Anda segera kami proses dan disiapkan oleh dapur.</p>
    </div>
</div>

<section class="bg-slate-50/70 py-10 sm:py-14 min-h-[60vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs overflow-hidden border border-slate-200/80">

            <div class="bg-gold-500/10 p-6 sm:p-8 border-b border-gold-500/20 text-center">
                <p class="text-xs text-gold-700 font-semibold uppercase tracking-wider mb-1">Total Tagihan</p>
                <h2 class="text-3xl sm:text-4xl font-bold font-serif text-charcoal-950 tabular-nums">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-2">Kode Pesanan: <strong class="font-mono text-charcoal-900">{{ $transaksi->kode_transaksi }}</strong></p>
                <div class="mt-3 flex justify-center gap-5 text-xs text-slate-500">
                    <span>Subtotal: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</strong></span>
                    @if($transaksi->ongkos_kirim > 0)
                        <span>Ongkir: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-7">

                <div class="text-center">
                    <h3 class="text-base font-serif font-bold text-charcoal-950 mb-3">Scan QRIS Niskala Samudra</h3>
                    <div class="inline-block p-4 bg-white border border-slate-200/80 rounded-2xl shadow-xs mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode('NiskalaSamudra-'.$transaksi->kode_transaksi.'-'.$transaksi->total_bayar) }}" alt="QRIS Niskala Samudra" class="w-48 h-48 sm:w-52 sm:h-52 mx-auto">
                    </div>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Gunakan aplikasi BCA Mobile, Gopay, OVO, Dana, ShopeePay, atau m-Banking Anda.</p>
                </div>

                <div class="relative flex py-1 items-center">
                    <div class="grow border-t border-slate-200"></div>
                    <span class="shrink-0 mx-4 text-slate-400 text-xs font-semibold uppercase tracking-wider">Atau Transfer Bank</span>
                    <div class="grow border-t border-slate-200"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="border border-slate-200/80 rounded-xl p-4 flex items-center gap-3.5 bg-slate-50/70 hover:border-gold-300 transition">
                        <div class="w-11 h-11 bg-white rounded-lg flex items-center justify-center font-bold text-blue-800 text-xs border border-slate-200 shadow-xs">BCA</div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">BCA Cabang Jepara</p>
                            <p class="font-mono font-bold text-charcoal-950 text-base">1234 5678 90</p>
                            <p class="text-xs text-slate-500">a.n. Niskala Samudra</p>
                        </div>
                    </div>
                    <div class="border border-slate-200/80 rounded-xl p-4 flex items-center gap-3.5 bg-slate-50/70 hover:border-gold-300 transition">
                        <div class="w-11 h-11 bg-white rounded-lg flex items-center justify-center font-bold text-emerald-700 text-xs border border-slate-200 shadow-xs">BSI</div>
                        <div>
                            <p class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Bank Syariah Indo</p>
                            <p class="font-mono font-bold text-charcoal-950 text-base">9876 5432 10</p>
                            <p class="text-xs text-slate-500">a.n. Niskala Samudra</p>
                        </div>
                    </div>
                </div>

                <div class="bg-charcoal-950 border border-charcoal-800/80 p-5 sm:p-6 rounded-2xl text-white">
                    <h3 class="text-white font-serif font-bold text-base mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-gold-400"></i> Upload Bukti Pembayaran
                    </h3>

                    <form action="{{ URL::temporarySignedRoute('checkout.qris.upload', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                        @csrf
                        <div>
                            <input type="file" name="bukti_pembayaran" required accept="image/jpeg,image/png,image/jpg" class="block w-full text-xs text-slate-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gold-500 file:text-charcoal-950 hover:file:bg-gold-600 transition cursor-pointer bg-charcoal-900 rounded-xl border border-charcoal-800 focus:outline-none">
                            @error('bukti_pembayaran')
                                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-2">
                            <span>Konfirmasi Pembayaran</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    localStorage.removeItem('niskala_cart');
</script>
@endsection
