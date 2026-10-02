@extends('template.back_karyawan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Pesanan Karyawan</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $transaksi->created_at->format('d M Y, H:i') }} · {{ $transaksi->tipe_pesanan }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
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
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-400">Alamat Pengantaran</p>
                        <p class="mt-1 leading-5 text-charcoal-900">{{ $transaksi->alamat }}</p>
                        @if($transaksi->patokan_alamat)
                            <p class="mt-1 text-xs text-blue-700 bg-blue-50 p-2 rounded-lg"><i class="fa-solid fa-map-pin mr-1"></i> Patokan: {{ $transaksi->patokan_alamat }}</p>
                        @endif
                        <div class="mt-3 flex gap-2">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($transaksi->alamat) }}"
                               target="_blank"
                               class="group inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-50/90 hover:bg-blue-600 text-blue-700 hover:text-white rounded-xl text-xs font-bold border border-blue-200/80 hover:border-blue-600 transition-all duration-200 shadow-2xs active:scale-95">
                                <i class="fa-solid fa-location-arrow text-xs text-blue-600 group-hover:text-white transition-colors duration-200"></i>
                                <span>Rute Maps</span>
                            </a>
                            @php $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $transaksi->no_hp_pelanggan)); @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}"
                               target="_blank"
                               class="group inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50/90 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl text-xs font-bold border border-emerald-200/80 hover:border-emerald-600 transition-all duration-200 shadow-2xs active:scale-95">
                                <i class="fa-brands fa-whatsapp text-sm text-emerald-600 group-hover:text-white transition-colors duration-200"></i>
                                <span>Chat WA</span>
                            </a>
                        </div>
                    </div>
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
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
            <h2 class="font-bold text-charcoal-900">Status & Log Operasional</h2>
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-bold text-blue-700">{{ $transaksi->status_pesanan }}</span>
                @if($transaksi->dibayar_pada)
                    <span class="text-xs text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Dibayar {{ $transaksi->dibayar_pada->format('d M Y, H:i') }}</span>
                @endif
            </div>

            @if($transaksi->tipe_pesanan === 'Delivery')
                <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Kurir Pengantar:</span>
                        <strong class="text-charcoal-900">{{ $transaksi->kurir->nama ?? 'Belum Ditugaskan' }}</strong>
                    </div>
                    @if($transaksi->waktu_berangkat)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Waktu Berangkat:</span>
                            <span class="text-charcoal-900 font-mono">{{ $transaksi->waktu_berangkat->format('d M Y, H:i') }} WIB</span>
                        </div>
                    @endif
                    @if($transaksi->waktu_sampai)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Waktu Sampai / Selesai:</span>
                            <span class="text-charcoal-900 font-mono">{{ $transaksi->waktu_sampai->format('d M Y, H:i') }} WIB</span>
                        </div>
                    @endif
                    @if($transaksi->durasi_pengantaran)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Durasi Pengantaran:</span>
                            <span class="text-emerald-700 font-bold">{{ $transaksi->durasi_pengantaran }}</span>
                        </div>
                    @endif
                    @if($transaksi->diterima_oleh)
                        <div class="flex justify-between">
                            <span class="text-slate-400">Diterima Oleh:</span>
                            <strong class="text-emerald-700">{{ $transaksi->diterima_oleh }}</strong>
                        </div>
                    @endif
                </div>

                @if($transaksi->bukti_pengantaran)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Bukti Serah Terima (POD)</p>
                        <a href="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" target="_blank" class="block w-32 h-24 rounded-xl overflow-hidden border border-slate-200 shadow-xs hover:opacity-90 transition">
                            <img src="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" alt="Bukti Pengantaran" class="w-full h-full object-cover">
                        </a>
                    </div>
                @endif
            @endif
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-charcoal-900">Catatan Pesanan</h2>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $transaksi->catatan ?: 'Tidak ada catatan tambahan.' }}</p>
        </div>
    </div>
</div>
@endsection
