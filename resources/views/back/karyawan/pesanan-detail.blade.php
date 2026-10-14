@extends('template.back_karyawan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Pesanan Karyawan</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $transaksi->created_at->format('d M Y, H:i') }} · {{ $transaksi->tipe_pesanan }}</p>
        </div>
        <div class="flex items-center gap-2.5 w-full sm:w-auto">
            <a href="{{ URL::temporarySignedRoute('front.struk', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]) }}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-charcoal-900 bg-gold-400 hover:bg-gold-500 rounded-xl transition shadow-xs">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </a>
            <a href="{{ url()->previous() }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition shadow-xs">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs sm:text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any() || session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                <div class="flex-1">
                    <p class="font-bold text-rose-950">Terjadi Kesalahan:</p>
                    @if(session('error'))
                        <p class="mt-0.5">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($transaksi->status_pesanan === 'Menunggu ACC')
        <div class="rounded-2xl border border-gold-300 bg-gold-50/70 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-gold-500/20 text-gold-800 flex items-center justify-center text-xl shrink-0 border border-gold-300/50">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base text-charcoal-950">Pesanan Masuk (Menunggu Konfirmasi)</h3>
                    <p class="text-xs text-slate-600 mt-0.5">Periksa rincian pesanan, lalu terima pesanan untuk meneruskannya ke antrean dapur.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-end">
                <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pembatalan Pesanan', 'Yakin ingin membatalkan pesanan {{ $transaksi->kode_transaksi }}? Stok porsi hidangan akan otomatis dikembalikan.')">
                    @csrf
                    <input type="hidden" name="status_pesanan" value="Dibatalkan">
                    <input type="hidden" name="status_pembayaran" value="Batal">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs sm:text-sm font-semibold rounded-xl transition shadow-2xs active:scale-95 flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Batalkan Pesanan</span>
                    </button>
                </form>

                @if($transaksi->metode_pembayaran == 'QRIS' && $transaksi->status_pembayaran == 'Belum Lunas')
                    <span class="w-full sm:w-auto px-4 py-2.5 bg-amber-50 text-amber-800 text-xs sm:text-sm font-semibold rounded-xl border border-amber-200 flex items-center justify-center gap-1.5 cursor-not-allowed">
                        <i class="fa-regular fa-clock"></i> Menunggu Pembayaran
                    </span>
                @elseif($transaksi->metode_pembayaran == 'QRIS' && $transaksi->status_pembayaran == 'Lunas')
                    <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Terima Pesanan', 'Terima pesanan {{ $transaksi->kode_transaksi }} dan teruskan ke dapur?')">
                        @csrf
                        <input type="hidden" name="status_pesanan" value="Dimasak">
                        <input type="hidden" name="status_pembayaran" value="Lunas">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 active:scale-95">
                            <i class="fa-solid fa-fire-burner"></i> Terima Pesanan
                        </button>
                    </form>
                @elseif($transaksi->metode_pembayaran == 'QRIS' && $transaksi->status_pembayaran == 'Menunggu Konfirmasi')
                    @if($transaksi->bukti_pembayaran)
                        <a href="{{ route('transaksi.bukti', $transaksi) }}" target="_blank" class="w-full sm:w-auto px-4 py-2.5 bg-charcoal-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-charcoal-800 transition shadow-xs flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-image"></i> Cek Bukti
                        </a>
                    @endif
                    <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Terima Pesanan & Verifikasi QRIS', 'Bukti pembayaran QRIS sudah valid? Terima pesanan ini untuk diteruskan ke dapur?')">
                        @csrf
                        <input type="hidden" name="status_pesanan" value="Dimasak">
                        <input type="hidden" name="status_pembayaran" value="Lunas">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 active:scale-95">
                            <i class="fa-solid fa-circle-check"></i> Valid & Terima Pesanan
                        </button>
                    </form>
                @else
                    <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Terima Pesanan', 'Terima pesanan {{ $transaksi->kode_transaksi }} untuk mulai dimasak?')">
                        @csrf
                        <input type="hidden" name="status_pesanan" value="Dimasak">
                        <input type="hidden" name="status_pembayaran" value="{{ $transaksi->metode_pembayaran === 'Tunai' ? 'Lunas' : $transaksi->status_pembayaran }}">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-1.5 active:scale-95">
                            <i class="fa-solid fa-fire-burner"></i> Terima Pesanan{{ $transaksi->metode_pembayaran === 'COD' ? ' (COD)' : '' }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @elseif($transaksi->status_pesanan === 'Dimasak')
        <div class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-800 flex items-center justify-center text-xl shrink-0 border border-amber-300/50">
                    <i class="fa-solid fa-fire-burner"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base text-charcoal-950">Sedang Dimasak di Dapur</h3>
                    <p class="text-xs text-slate-600 mt-0.5">Jika hidangan sudah selesai disiapkan, perbarui status pesanan.</p>
                </div>
            </div>
            <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pesanan Selesai Dimasak', 'Pastikan semua hidangan telah selesai dimasak dan dikemas dengan rapi.')">
                @csrf
                <input type="hidden" name="status_pesanan" value="{{ $transaksi->tipe_pesanan === 'Delivery' ? 'Siap Diantar' : 'Selesai' }}">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-charcoal-950 hover:text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-check"></i>
                    <span>{{ $transaksi->tipe_pesanan === 'Delivery' ? 'Selesai Dimasak (Siap Diantar)' : 'Selesai Dimasak & Sajikan' }}</span>
                </button>
            </form>
        </div>
    @endif

    @if($transaksi->status_pesanan !== 'Menunggu ACC' && $transaksi->status_pembayaran !== 'Lunas' && !in_array($transaksi->status_pesanan, ['Dibatalkan', 'Batal']))
        <div class="rounded-2xl border border-amber-300 bg-amber-50/90 p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-800 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base text-charcoal-950">Tagihan Kasir: Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</h3>
                    <p class="text-xs text-amber-800/80 mt-0.5">Status saat ini: <strong class="text-amber-950">{{ $transaksi->status_pembayaran }}</strong> · Metode: <strong>{{ $transaksi->payment_channel_label }}</strong></p>
                </div>
            </div>
            <form action="{{ route('karyawan.update_status', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Terima Pembayaran', 'Konfirmasi bahwa uang pembayaran sebesar Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }} telah diterima di kasir?')">
                @csrf
                <input type="hidden" name="status_pesanan" value="{{ $transaksi->status_pesanan }}">
                <input type="hidden" name="status_pembayaran" value="Lunas">
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Terima Pembayaran (Lunas)</span>
                </button>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pelanggan</p>
            <h2 class="mt-2 text-lg font-bold text-charcoal-900">{{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan ?? 'Guest' }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan ?? 'Nomor HP tidak tersedia' }}</p>
            <div class="mt-5 pt-5 border-t border-slate-100 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span class="text-slate-400">Tipe</span><strong class="text-charcoal-900">{{ $transaksi->tipe_pesanan }}</strong></div>
                <div class="flex justify-between gap-4"><span class="text-slate-400">Pembayaran</span><strong class="text-charcoal-900 text-right">{{ $transaksi->payment_channel_label }}</strong></div>
                <div class="flex justify-between items-center gap-4">
                    <span class="text-slate-400">Status Bayar</span>
                    @if($transaksi->status_pembayaran === 'Lunas')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xs">
                            Lunas
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 font-bold text-xs">
                            {{ $transaksi->status_pembayaran }}
                        </span>
                    @endif
                </div>
                @if($transaksi->payment_reference)
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">No. Referensi</span>
                        <span class="font-mono font-semibold text-xs text-charcoal-900">{{ $transaksi->payment_reference }}</span>
                    </div>
                @endif
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
