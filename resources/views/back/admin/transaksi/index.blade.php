@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Data Transaksi</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola pesanan masuk, pembayaran, dan status pengiriman.</p>
            </div>
        </div>
        <a href="{{ route('admin.transaksi.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
            <i class="fa-solid fa-plus"></i> Kasir Baru
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-2">
        <div class="flex flex-wrap gap-2">
            @php $currentStatus = request('status', 'Semua'); @endphp

            <a href="{{ route('admin.transaksi.index', ['search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Semua' ? 'bg-charcoal-900 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                Semua Pesanan
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'Menunggu ACC', 'search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Menunggu ACC' ? 'bg-red-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                <i class="fa-solid fa-bell-concierge mr-1 text-[10px]"></i> Menunggu ACC
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'Dimasak', 'search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Dimasak' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                <i class="fa-solid fa-fire-burner mr-1 text-[10px]"></i> Dimasak
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'Diantar', 'search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Diantar' ? 'bg-sky-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                <i class="fa-solid fa-motorcycle mr-1 text-[10px]"></i> Diantar
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'Selesai', 'search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                <i class="fa-solid fa-check-double mr-1 text-[10px]"></i> Selesai
            </a>
            <a href="{{ route('admin.transaksi.index', ['status' => 'Dibatalkan', 'search' => request('search')]) }}"
               class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $currentStatus == 'Dibatalkan' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200/80 hover:bg-slate-50' }}">
                <i class="fa-solid fa-ban mr-1 text-[10px]"></i> Dibatalkan
            </a>
        </div>

        <form action="{{ route('admin.transaksi.index') }}" method="GET" class="w-full lg:w-72 relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2 text-xs sm:text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-xs">
            <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 lg:py-5">Kode / Tanggal</th>
                        <th class="px-6 py-4 lg:py-5">Pelanggan & Tipe</th>
                        <th class="px-6 py-4 lg:py-5">Total Bayar</th>
                        <th class="px-6 py-4 lg:py-5">Status</th>
                        <th class="px-6 py-4 lg:py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksi as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <p class="font-mono font-bold text-charcoal-950">{{ $item->kode_transaksi }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $item->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-charcoal-900">{{ $item->pelanggan->nama ?? $item->nama_pelanggan ?? 'Pelanggan Umum' }}</p>
                                <span class="inline-flex items-center gap-1 mt-1 text-xs font-medium text-slate-500">
                                    <i class="fa-solid fa-bell-concierge text-[10px]"></i> {{ $item->tipe_pesanan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gold-600 tabular-nums">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $item->payment_channel_label }}</p>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <div>
                                    @if($item->status_pembayaran == 'Lunas')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-shield-halved text-[9px]"></i> Lunas
                                        </span>
                                    @elseif($item->status_pembayaran == 'Batal')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">Batal</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">{{ $item->status_pembayaran }}</span>
                                    @endif
                                </div>
                                <div>
                                    @if($item->status_pesanan == 'Selesai')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $item->status_pesanan }}</span>
                                    @elseif($item->status_pesanan == 'Dibatalkan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">{{ $item->status_pesanan }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200">{{ $item->status_pesanan }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('admin.transaksi.show', $item->kode_transaksi) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-blue-600 text-slate-500 hover:text-white border border-slate-200/80 hover:border-blue-600 transition-all duration-200 shadow-2xs active:scale-95" title="Detail Invoice">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.transaksi.edit', $item->kode_transaksi) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-charcoal-950 text-slate-500 hover:text-gold-400 border border-slate-200/80 hover:border-charcoal-950 transition-all duration-200 shadow-2xs active:scale-95" title="Update Status">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="{{ route('admin.transaksi.destroy', $item->kode_transaksi) }}" method="POST" class="inline-block" @submit.prevent="openDeleteModal($event, 'Hapus transaksi ini? Stok menu akan dikembalikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-rose-600 text-slate-500 hover:text-white border border-slate-200/80 hover:border-rose-600 transition-all duration-200 shadow-2xs active:scale-95" title="Hapus Transaksi">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                    <i class="fa-solid fa-cash-register text-4xl text-slate-300"></i>
                                    <p class="text-base font-medium">Belum ada data transaksi {{ $currentStatus !== 'Semua' ? 'untuk status ini' : '' }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksi->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                <div class="max-w-full overflow-x-auto pb-1">
                    {{ $transaksi->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
