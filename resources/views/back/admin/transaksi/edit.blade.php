@extends('template.back_admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0">
            <i class="fa-solid fa-list-check text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Update Status Transaksi</h1>
            <p class="text-sm text-slate-500 mt-1">Invoice: <span class="font-mono font-bold">{{ $transaksi->kode_transaksi }}</span></p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <form action="{{ route('admin.transaksi.update', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Perubahan Status', 'Yakin ingin menyimpan perubahan status transaksi ini?')" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="status_pembayaran" class="block text-sm font-semibold text-charcoal-900 mb-2">Status Pembayaran</label>
                <select name="status_pembayaran" id="status_pembayaran" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 block w-full px-4 py-3 transition-all">
                    @foreach($statusPembayaranOptions as $statusPembayaran)
                        <option value="{{ $statusPembayaran }}" {{ $transaksi->status_pembayaran === $statusPembayaran ? 'selected' : '' }}>
                            {{ $statusPembayaran === 'Lunas' ? 'Lunas (Telah Dibayar)' : $statusPembayaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status_pesanan" class="block text-sm font-semibold text-charcoal-900 mb-2">Progress Pesanan</label>
                <select name="status_pesanan" id="status_pesanan" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 block w-full px-4 py-3 transition-all">
                    @foreach($statusPesananOptions as $statusPesanan)
                        <option value="{{ $statusPesanan }}" {{ $transaksi->status_pesanan === $statusPesanan ? 'selected' : '' }}>{{ $statusPesanan }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-2">Pastikan status pesanan disesuaikan dengan realita di dapur/lapangan.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.transaksi.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm active:scale-95 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
