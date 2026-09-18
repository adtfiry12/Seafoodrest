@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-12 border-b-4 border-gold-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-4xl font-serif text-white mb-3">Pembayaran QRIS / Transfer</h1>
        <p class="text-slate-400 text-sm font-light">Selesaikan pembayaran agar pesanan Anda segera kami proses.</p>
    </div>
</div>

<section class="bg-slate-50 py-12 min-h-[60vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">

            <div class="bg-gold-50 p-6 sm:p-8 border-b border-gold-100 text-center">
                <p class="text-xs text-gold-700 font-bold uppercase tracking-wider mb-2">Total Tagihan</p>
                <h2 class="text-4xl font-bold text-charcoal-900">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</h2>
                <p class="text-sm text-slate-500 mt-2 font-mono">Kode Pesanan: <strong>{{ $transaksi->kode_transaksi }}</strong></p>
                <div class="mt-4 flex justify-center gap-5 text-xs text-slate-500">
                    <span>Subtotal: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</strong></span>
                    @if($transaksi->ongkos_kirim > 0)
                        <span>Ongkir: <strong class="text-charcoal-900">Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">

                <div class="text-center">
                    <h3 class="text-lg font-bold text-charcoal-900 mb-4">Scan QRIS Niskala Samudra</h3>
                    <div class="inline-block p-4 bg-white border-2 border-slate-200 rounded-2xl shadow-sm mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode('NiskalaSamudra-'.$transaksi->kode_transaksi.'-'.$transaksi->total_bayar) }}" alt="QRIS Niskala Samudra" class="w-48 h-48 sm:w-56 sm:h-56 mx-auto">
                    </div>
                    <p class="text-sm text-slate-500">Gunakan aplikasi m-BCA, Gopay, OVO, Dana, atau LinkAja Anda.</p>
                </div>

                <div class="relative flex py-2 items-center">
                    <div class="grow border-t border-slate-200"></div>
                    <span class="shrink-0 mx-4 text-slate-400 text-xs font-bold uppercase tracking-wider">Atau Transfer Bank</span>
                    <div class="grow border-t border-slate-200"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-slate-200 rounded-xl p-4 flex items-center gap-4 bg-slate-50">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center font-bold text-blue-800 text-xs border border-slate-200 shadow-sm">BCA</div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">BCA Cabang Jepara</p>
                            <p class="font-mono font-bold text-charcoal-900 text-lg">1234 5678 90</p>
                            <p class="text-xs text-slate-500">a.n. Niskala Samudra</p>
                        </div>
                    </div>
                    <div class="border border-slate-200 rounded-xl p-4 flex items-center gap-4 bg-slate-50">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center font-bold text-orange-600 text-xs border border-slate-200 shadow-sm">BSI</div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Bank Syariah Indo</p>
                            <p class="font-mono font-bold text-charcoal-900 text-lg">9876 5432 10</p>
                            <p class="text-xs text-slate-500">a.n. Niskala Samudra</p>
                        </div>
                    </div>
                </div>

                <div class="bg-charcoal-950 p-6 rounded-2xl mt-8">
                    <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-cloud-arrow-up text-gold-500"></i> Upload Bukti Pembayaran
                    </h3>

                    <form action="{{ URL::temporarySignedRoute('checkout.qris.upload', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <input type="file" name="bukti_pembayaran" required accept="image/jpeg,image/png,image/jpg" class="block w-full text-sm text-slate-400 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-gold-600 file:text-white hover:file:bg-gold-500 transition cursor-pointer bg-charcoal-900 rounded-xl border border-charcoal-800 focus:outline-none">
                            @error('bukti_pembayaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="w-full px-8 py-3.5 bg-gold-600 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-gold-500 transition shadow-lg shadow-gold-600/20 active:scale-95 text-center">
                            Konfirmasi Pembayaran
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
