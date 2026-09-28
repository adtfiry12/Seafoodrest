@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Laporan Penjualan</h1>
                <p class="text-sm text-slate-500 mt-1">Rekapitulasi transaksi yang sudah diselesaikan.</p>
            </div>
        </div>

        <!-- Filter Bulan & Tahun -->
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap gap-2 w-full sm:w-auto">
            <select name="bulan" class="w-full sm:w-auto bg-white border border-slate-200 text-sm rounded-lg focus:ring-gold-500 px-3 py-2 shadow-sm">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="w-full sm:w-auto bg-white border border-slate-200 text-sm rounded-lg focus:ring-gold-500 px-3 py-2 shadow-sm">
                @for($i = date('Y'); $i >= date('Y') - 3; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-charcoal-900 text-white text-sm font-semibold rounded-lg hover:bg-charcoal-800 transition shadow-sm flex items-center justify-center gap-2"><i class="fa-solid fa-filter"></i> Filter</button>
        </form>
    </div>

    <!-- Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-2xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-bold text-charcoal-900 mt-1">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-2xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Pesanan Selesai</p>
                <p class="text-2xl font-bold text-charcoal-900 mt-1">{{ $total_pesanan }} Transaksi</p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kode Transaksi</th>
                        <th class="px-6 py-4">Tipe Pesanan</th>
                        <th class="px-6 py-4 text-right">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($laporan as $item)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-charcoal-900">
                                <a href="{{ route('admin.transaksi.show', $item->kode_transaksi) }}" class="hover:text-gold-600 transition">{{ $item->kode_transaksi }}</a>
                            </td>
                            <td class="px-6 py-4">{{ $item->tipe_pesanan }}</td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">Tidak ada data penjualan di bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($laporan->hasPages())
            <div class="border-t border-slate-200 bg-slate-50/50 px-6 py-4">
                <div class="max-w-full overflow-x-auto pb-1">
                    {{ $laporan->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
