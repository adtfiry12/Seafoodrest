@extends('template.back_admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Detail Invoice</h1>
            <p class="text-sm text-slate-500 mt-1">Kode: <span class="font-mono font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</span></p>
        </div>

        <div class="flex flex-col sm:flex-row flex-wrap gap-2 w-full sm:w-auto">
            <a href="{{ route('admin.transaksi.cetak', $transaksi->kode_transaksi) }}" target="_blank" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-charcoal-900 bg-gold-500 rounded-lg hover:bg-gold-600 transition shadow-sm">
                <i class="fa-solid fa-print mr-2"></i> Cetak Struk
            </a>

            <a href="{{ route('admin.transaksi.edit', $transaksi->kode_transaksi) }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-white bg-charcoal-900 rounded-lg hover:bg-charcoal-800 transition">
                Update Status
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <!-- Header Struk -->
        <div class="p-6 sm:p-8 bg-charcoal-950 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-serif text-2xl font-bold tracking-wide">Niskala<span class="text-gold-500">Samudra</span></h2>
                <p class="text-slate-400 text-sm mt-1">Invoice Transaksi Restoran</p>
            </div>
            <div class="text-left sm:text-right text-sm space-y-1">
                <p><span class="text-slate-400">Kasir:</span> {{ $transaksi->karyawan->nama ?? 'Sistem' }}</p>
                <p><span class="text-slate-400">Tgl:</span> {{ $transaksi->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <!-- Info Pemesan -->
        <div class="p-6 sm:p-8 border-b border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pelanggan</p>
                <p class="font-bold text-charcoal-900">{{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan ?? 'Pelanggan Umum (Guest)' }}</p>
                @if($transaksi->pelanggan?->no_hp || $transaksi->no_hp_pelanggan)
                    <p class="text-sm text-slate-600">{{ $transaksi->pelanggan->no_hp ?? $transaksi->no_hp_pelanggan }}</p>
                @endif
                <p class="text-sm font-medium mt-2"><span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">{{ $transaksi->tipe_pesanan }}</span></p>

                @if($transaksi->tipe_pesanan == 'Delivery')
                    <div class="mt-3 p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-1">
                        <p><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
                        @if($transaksi->patokan_alamat)
                            <p class="text-gold-700"><strong>Patokan:</strong> {{ $transaksi->patokan_alamat }}</p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="sm:text-right">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pembayaran</p>
                <p class="font-bold text-charcoal-900 mb-1">{{ $transaksi->metode_pembayaran }}</p>

                @if($transaksi->status_pembayaran == 'Lunas')
                    <span class="inline-flex px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">LUNAS</span>
                @else
                    <span class="inline-flex px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">{{ strtoupper($transaksi->status_pembayaran) }}</span>
                @endif

                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-4 mb-1">Status Dapur/Pengiriman</p>
                <span class="inline-flex px-3 py-1 bg-slate-100 text-charcoal-900 border border-slate-200 text-xs font-bold rounded-full">{{ strtoupper($transaksi->status_pesanan) }}</span>
            </div>
        </div>

        @if($transaksi->tipe_pesanan == 'Delivery')
            <!-- Info Ekspedisi Pengantaran Admin -->
            <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h4 class="text-sm font-bold text-charcoal-900 uppercase tracking-wider mb-3 border-l-4 border-gold-500 pl-3 flex items-center justify-between">
                    <span>Informasi Ekspedisi Pengantaran</span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $transaksi->status_pesanan === 'Selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-gold-100 text-gold-800' }}">
                        {{ $transaksi->status_pesanan }}
                    </span>
                </h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-3 text-sm">
                    <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Kurir Ditugaskan</span>
                        <span class="font-bold text-charcoal-900 block mt-1">{{ $transaksi->kurir->nama ?? 'Belum Ditugaskan' }}</span>
                        <span class="text-xs text-slate-500">{{ $transaksi->kurir ? 'Staff Kurir' : 'Menunggu pickup' }}</span>
                    </div>

                    <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Waktu Berangkat</span>
                        <span class="font-bold text-charcoal-900 block mt-1">{{ $transaksi->waktu_berangkat ? $transaksi->waktu_berangkat->format('H:i') . ' WIB' : '-' }}</span>
                        <span class="text-xs text-slate-500">{{ $transaksi->waktu_berangkat ? $transaksi->waktu_berangkat->format('d M Y') : 'Belum jalan' }}</span>
                    </div>

                    <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Waktu Tiba / Estimasi</span>
                        <span class="font-bold text-charcoal-900 block mt-1">
                            @if($transaksi->waktu_sampai)
                                {{ $transaksi->waktu_sampai->format('H:i') . ' WIB' }}
                                @if($transaksi->durasi_pengantaran)
                                    <span class="text-[10px] font-semibold text-emerald-600 block">({{ $transaksi->durasi_pengantaran }})</span>
                                @endif
                            @elseif($transaksi->status_pesanan === 'Diantar')
                                <span class="text-amber-600 text-xs font-semibold">{{ $transaksi->estimasi_sampai }}</span>
                            @else
                                <span class="text-slate-400 font-normal">25 - 35 Menit</span>
                            @endif
                        </span>
                        <span class="text-xs text-slate-500">Penerima: <strong>{{ $transaksi->diterima_oleh ?? '-' }}</strong></span>
                    </div>

                    <div class="bg-white p-3.5 rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-400 block font-semibold uppercase">Patokan Alamat</span>
                        <span class="font-bold text-charcoal-900 block mt-1 text-xs leading-5">{{ $transaksi->patokan_alamat ?? 'Tidak ada patokan' }}</span>
                    </div>
                </div>

                @if($transaksi->bukti_pengantaran)
                    <div class="mt-4 p-4 bg-white rounded-xl border border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" target="_blank" class="block w-20 h-20 rounded-lg overflow-hidden border border-slate-200 group relative shrink-0">
                            <img src="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" alt="Bukti Serah Terima" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <div class="absolute inset-0 bg-charcoal-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs transition-opacity">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </a>
                        <div class="text-xs text-slate-600 space-y-1">
                            <p class="font-bold text-sm text-charcoal-900 flex items-center gap-1.5">
                                <i class="fa-solid fa-camera text-gold-500"></i> Bukti Foto Serah Terima (Proof of Delivery)
                            </p>
                            <p>Foto bukti serah terima telah diunggah oleh kurir saat pesanan diserahkan kepada <strong>{{ $transaksi->diterima_oleh }}</strong>.</p>
                            <a href="{{ asset('storage/' . $transaksi->bukti_pengantaran) }}" target="_blank" class="inline-flex items-center gap-1 text-gold-600 font-bold hover:underline mt-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Foto Ukuran Penuh
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- INI BAGIAN BUKTI PEMBAYARAN (MUNCUL JIKA QRIS) -->
        @if($transaksi->metode_pembayaran == 'QRIS')
            <div class="px-6 sm:px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h4 class="text-sm font-bold text-charcoal-900 uppercase tracking-wider mb-4 border-l-4 border-gold-500 pl-3">Bukti Pembayaran QRIS</h4>

                @if($transaksi->bukti_pembayaran)
                    <div class="flex flex-col sm:flex-row gap-6 items-start">
                        <a href="{{ route('transaksi.bukti', $transaksi) }}" target="_blank" class="block w-full sm:w-48 shrink-0 overflow-hidden rounded-xl border-2 border-slate-200 shadow-sm hover:opacity-80 transition group relative">
                            <img src="{{ route('transaksi.bukti', $transaksi) }}" alt="Bukti Transfer" class="w-full h-auto sm:h-64 object-cover">
                            <div class="absolute inset-0 bg-charcoal-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fa-solid fa-magnifying-glass-plus text-white text-3xl"></i>
                            </div>
                        </a>
                        <div class="flex-1 space-y-3">
                            <p class="text-sm text-charcoal-900 font-medium">Pelanggan telah mengunggah bukti pembayaran.</p>
                            <p class="text-xs text-slate-500 mb-4"><i class="fa-solid fa-circle-info text-blue-500 mr-1"></i> Klik gambar di samping untuk melihat struk lebih jelas (zoom).</p>

                            <!-- Tombol Shortcut ACC Khusus Pembayaran -->
                            @if($transaksi->status_pembayaran == 'Menunggu Konfirmasi')
                                <form action="{{ route('admin.transaksi.update', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Pembayaran', 'Tandai pembayaran lunas dan teruskan pesanan ke dapur?')" class="mt-4 p-4 bg-white border border-slate-200 rounded-xl">
                                    @csrf
                                    @method('PUT')
                                    <p class="text-xs text-slate-500 mb-2 font-bold">Aksi Cepat:</p>
                                    <input type="hidden" name="status_pembayaran" value="Lunas">
                                    <input type="hidden" name="status_pesanan" value="Dimasak">
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-lg hover:bg-emerald-600 shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-check-double"></i> Tandai Lunas & Masak
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-orange-50 text-orange-700 rounded-xl text-sm border border-orange-200 flex flex-col sm:flex-row items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center shrink-0 text-xl">
                            <i class="fa-solid fa-hourglass-half animate-pulse"></i>
                        </div>
                        <div>
                            <p class="font-bold">Menunggu Upload</p>
                            <p class="text-xs">Pelanggan belum mengunggah foto bukti pembayaran. Harap tunggu.</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Tabel Item -->
        <div class="overflow-x-auto p-6 sm:p-8">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 text-charcoal-900">
                    <tr>
                        <th class="py-3 font-bold">Item Menu</th>
                        <th class="py-3 font-bold text-center">Harga</th>
                        <th class="py-3 font-bold text-center">Qty</th>
                        <th class="py-3 font-bold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaksi->detail as $dt)
                        <tr>
                            <td class="py-4 font-medium">{{ $dt->menu->nama_menu ?? $dt->nama_menu }}</td>
                            <td class="py-4 text-center">Rp {{ number_format($dt->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-4 text-center font-bold">{{ $dt->qty }}</td>
                            <td class="py-4 text-right font-bold text-charcoal-900">Rp {{ number_format($dt->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="py-2 text-right text-slate-500">Subtotal</td>
                        <td class="py-2 text-right font-medium">Rp {{ number_format($transaksi->subtotal_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @if($transaksi->ongkos_kirim > 0)
                        <tr>
                            <td colspan="3" class="py-2 text-right text-slate-500">Ongkos Kirim</td>
                            <td class="py-2 text-right font-medium">Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="py-4 text-right font-bold text-slate-500 uppercase tracking-wider text-xs">Total Pembayaran</td>
                        <td class="py-4 text-right font-bold text-gold-600 text-xl">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($transaksi->catatan)
            <div class="p-6 sm:p-8 border-t border-slate-100 bg-slate-50">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Tambahan</p>
                <p class="text-sm italic text-charcoal-900">"{!! nl2br(e($transaksi->catatan)) !!}"</p>
            </div>
        @endif
    </div>
</div>
@endsection
