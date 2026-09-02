@extends('template.back_admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Detail Invoice</h1>
            <p class="text-sm text-slate-500 mt-1">Kode: <span class="font-mono font-bold text-charcoal-900">{{ $transaksi->kode_transaksi }}</span></p>
        </div>

        <!-- BAGIAN INI YANG DIUPDATE: Tambah tombol Cetak & ubah param jadi kode_transaksi -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.transaksi.cetak', $transaksi->kode_transaksi) }}" target="_blank" class="px-4 py-2 text-sm font-medium text-charcoal-900 bg-gold-500 rounded-lg hover:bg-gold-600 transition shadow-sm">
                <i class="fa-solid fa-print mr-2"></i> Cetak Struk
            </a>

            <a href="{{ route('admin.transaksi.edit', $transaksi->kode_transaksi) }}" class="px-4 py-2 text-sm font-medium text-white bg-charcoal-900 rounded-lg hover:bg-charcoal-800 transition">
                Update Status
            </a>
            <a href="{{ route('admin.transaksi.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
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
                <p class="font-bold text-charcoal-900">{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum (Guest)' }}</p>
                @if($transaksi->pelanggan)
                    <p class="text-sm text-slate-600">{{ $transaksi->pelanggan->no_hp }}</p>
                @endif
                <p class="text-sm font-medium mt-2"><span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-200">{{ $transaksi->tipe_pesanan }}</span></p>

                @if($transaksi->tipe_pesanan == 'Delivery')
                    <p class="text-sm text-slate-600 mt-2"><strong>Alamat:</strong> {{ $transaksi->alamat }}</p>
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
                            <td class="py-4 font-medium">{{ $dt->menu->nama_menu ?? 'Menu Terhapus' }}</td>
                            <td class="py-4 text-center">Rp {{ number_format($dt->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-4 text-center font-bold">{{ $dt->qty }}</td>
                            <td class="py-4 text-right font-bold text-charcoal-900">Rp {{ number_format($dt->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
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
                <p class="text-sm italic text-charcoal-900">"{{ $transaksi->catatan }}"</p>
            </div>
        @endif
    </div>
</div>
@endsection
