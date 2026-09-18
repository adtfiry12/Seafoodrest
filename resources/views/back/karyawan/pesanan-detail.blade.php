@extends('template.back_karyawan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Pesanan Karyawan</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $transaksi->created_at->format('d M Y, H:i') }} · {{ $transaksi->tipe_pesanan }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pelanggan</p>
            <h2 class="mt-2 text-lg font-bold text-charcoal-900">{{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan ?? 'Guest' }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan ?? 'Nomor HP tidak tersedia' }}</p>
            <div class="mt-5 pt-5 border-t border-slate-100 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span class="text-slate-400">Tipe</span><strong class="text-charcoal-900">{{ $transaksi->tipe_pesanan }}</strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">Pembayaran</span><strong class="text-charcoal-900">{{ $transaksi->metode_pembayaran }}</strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">Status bayar</span><strong class="text-charcoal-900">{{ $transaksi->status_pembayaran }}</strong></div>
                @if($transaksi->alamat)
                    <div class="pt-3 border-t border-slate-100"><p class="text-xs text-slate-400">Alamat delivery</p><p class="mt-1 leading-5 text-charcoal-900">{{ $transaksi->alamat }}</p></div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-b border-slate-100">
                <div><h2 class="font-bold text-charcoal-900">Rincian Menu</h2><p class="mt-1 text-xs text-slate-500">Snapshot pesanan saat transaksi dibuat.</p></div>
                <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">{{ $transaksi->detail->sum('qty') }} Porsi</span>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($transaksi->detail as $detail)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div><p class="font-semibold text-charcoal-900">{{ $detail->menu->nama_menu ?? $detail->nama_menu }}</p><p class="mt-1 text-xs text-slate-500">{{ $detail->qty }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</p></div>
                        <p class="font-bold text-gold-600">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-slate-200 bg-slate-50 px-5 py-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-500">Subtotal</span><span>Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</span></div>
                @if($transaksi->ongkos_kirim > 0)
                    <div class="flex justify-between"><span class="text-slate-500">Ongkos kirim</span><span>Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</span></div>
                @endif
                <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold"><span>Total</span><span class="text-gold-600">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-charcoal-900">Status Operasional</h2>
            <div class="mt-4 flex items-center gap-3">
                <span class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-blue-700">{{ $transaksi->status_pesanan }}</span>
                @if($transaksi->dibayar_pada)
                    <span class="text-xs text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Dibayar {{ $transaksi->dibayar_pada->format('d M Y, H:i') }}</span>
                @endif
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-charcoal-900">Catatan Pesanan</h2>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $transaksi->catatan ?: 'Tidak ada catatan tambahan.' }}</p>
        </div>
    </div>
</div>
@endsection
