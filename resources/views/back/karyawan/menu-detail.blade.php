@extends('template.back_karyawan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <div class="inline-flex items-center justify-center w-11 h-11 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Menu Karyawan</p>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $menu->nama_menu }}</h1>
            </div>
        </div>
        <a href="{{ route('karyawan.stok') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Stok
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="self-start bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="h-32 bg-slate-100">
                @if($menu->gambar)
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center w-full h-full text-slate-300"><i class="fa-solid fa-image text-5xl"></i></div>
                @endif
            </div>
            <div class="p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-gold-600">{{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                <h2 class="mt-1 text-lg font-bold leading-tight text-charcoal-900">{{ $menu->nama_menu }}</h2>
                <p class="mt-2 line-clamp-3 text-sm leading-5 text-slate-500">{{ $menu->deskripsi ?: 'Belum ada deskripsi menu.' }}</p>
            </div>
        </div>

        <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-4 content-start">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Stok</p>
                <p class="mt-2 text-2xl font-bold {{ $menu->stok == 0 ? 'text-red-600' : ($menu->stok <= 5 ? 'text-orange-500' : 'text-charcoal-900') }}">{{ $menu->stok }}</p>
                <p class="mt-1 text-xs text-slate-500">Porsi tersedia</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Harga</p>
                <p class="mt-2 text-lg font-bold text-gold-600">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Harga jual</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Pesanan</p>
                <p class="mt-2 text-2xl font-bold text-charcoal-900">{{ $transaksi->total() }}</p>
                <p class="mt-1 text-xs text-slate-500">Riwayat transaksi</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <p class="text-xs text-slate-400 uppercase tracking-wider">Status</p>
                @if($menu->is_active)
                    <p class="mt-2 text-sm font-bold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Aktif</p>
                @else
                    <p class="mt-2 text-sm font-bold text-red-600"><i class="fa-solid fa-circle-xmark mr-1"></i> Nonaktif</p>
                @endif
                <p class="mt-1 text-xs text-slate-500">Tampilan menu</p>
            </div>

            <div class="col-span-2 sm:col-span-4 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-bold text-charcoal-900">Pesanan Terkait Menu Ini</h2>
                    <p class="mt-1 text-xs text-slate-500">Ringkasan operasional untuk membantu menyiapkan pesanan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Transaksi</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3">Qty</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transaksi as $item)
                                @php $detail = $item->detail->first(); @endphp
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-5 py-4">
                                        <p class="font-mono font-bold text-charcoal-900">{{ $item->kode_transaksi }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-charcoal-900">{{ $item->pelanggan->nama ?? $item->nama_pelanggan ?? 'Guest' }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $item->tipe_pesanan }}</p>
                                    </td>
                                    <td class="px-5 py-4 font-bold text-charcoal-900">{{ $detail?->qty ?? 0 }}x</td>
                                    <td class="px-5 py-4 font-bold text-gold-600">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-md px-2.5 py-1 text-xs font-semibold {{ $item->status_pesanan === 'Selesai' ? 'bg-emerald-50 text-emerald-600' : ($item->status_pesanan === 'Dibatalkan' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600') }}">{{ $item->status_pesanan }}</span>
                                        <p class="mt-1 text-[11px] text-slate-400">{{ $item->status_pembayaran }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                        <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                                        <p class="mt-2 text-sm">Belum ada pesanan untuk menu ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transaksi->hasPages())
                    <div class="border-t border-slate-200 bg-slate-50/50 px-5 py-4 overflow-x-auto">
                        {{ $transaksi->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
