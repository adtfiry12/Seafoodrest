@extends('template.back_admin')

@section('content')

<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="bg-charcoal-950 rounded-2xl p-6 sm:p-8 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-lg overflow-hidden relative">
        <div class="absolute -right-10 -top-10 opacity-10 pointer-events-none">
            <i class="fa-solid fa-anchor text-9xl"></i>
        </div>

        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-serif font-bold tracking-wide">
                Selamat Datang, <span class="text-gold-500">{{ Auth::user()->nama ?? 'Admin' }}</span>! 👋
            </h1>
            <p class="text-slate-400 mt-2 text-sm sm:text-base">
                Berikut adalah ringkasan performa dan operasional Niskala Samudra hari ini, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}.
            </p>
        </div>
        <div class="relative z-10 hidden md:block">
            <div class="bg-charcoal-900 border border-charcoal-800 px-4 py-2 rounded-xl text-center">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Jam Saat Ini</p>
                <p class="text-xl font-bold text-gold-400 font-mono" x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }" x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), 1000)" x-text="time"></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-charcoal-900 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pesanan Menunggu</p>
                    <h3 class="text-2xl font-bold text-charcoal-900 mt-2">{{ $pesananBaru }} <span class="text-sm text-slate-500 font-normal">Antrian</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Menu</p>
                    <h3 class="text-2xl font-bold text-charcoal-900 mt-2">{{ $totalMenu }} <span class="text-sm text-slate-500 font-normal">Item Aktif</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-utensils"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pelanggan</p>
                    <h3 class="text-2xl font-bold text-charcoal-900 mt-2">{{ $totalPelanggan }} <span class="text-sm text-slate-500 font-normal">Akun</span></h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-4">
                <h3 class="font-bold text-charcoal-900">Grafik Pendapatan (7 Hari Terakhir)</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-charcoal-900 border-b border-slate-100 pb-4 mb-4">Menu Paling Laris</h3>
            <div class="space-y-4">
                @forelse($topMenus as $top)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                            @if($top->menu && $top->menu->gambar)
                                <img src="{{ asset('storage/' . $top->menu->gambar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-slate-400"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-sm text-charcoal-900">{{ $top->menu->nama_menu ?? 'Menu Terhapus' }}</h4>
                            <p class="text-xs text-slate-500">{{ $top->total_terjual }} Porsi Terjual</p>
                        </div>
                        <div class="text-right">
                            @if($top->menu)
                                <span class="text-xs font-bold text-gold-600">Rp {{ number_format($top->menu->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-sm">Belum ada data penjualan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-charcoal-900">5 Transaksi Terbaru</h3>
            <a href="{{ route('admin.transaksi.index') }}" class="text-sm font-semibold text-gold-600 hover:text-gold-700">Lihat Semua &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerbaru as $trx)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-charcoal-900">{{ $trx->kode_transaksi }}</td>
                            <td class="px-6 py-4">
                                {{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}
                                <div class="text-xs text-slate-400">{{ $trx->tipe_pesanan }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gold-600">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($trx->status_pesanan == 'Menunggu ACC')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-red-700">Menunggu</span>
                                @elseif($trx->status_pesanan == 'Selesai')
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">Selesai</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-600 border border-blue-200">{{ $trx->status_pesanan }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.transaksi.show', $trx->kode_transaksi) }}" title="Lihat detail transaksi" aria-label="Lihat detail transaksi" class="inline-flex items-center justify-center w-9 h-9 bg-slate-100 text-slate-500 hover:bg-gold-50 hover:text-gold-600 rounded-lg transition-colors">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('salesChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        const chartLabels = {!! json_encode($chartDates ?? []) !!};
        const chartData = {!! json_encode($chartTotals ?? [], JSON_NUMERIC_CHECK) !!};

        console.log("Tanggal:", chartLabels);
        console.log("Total Rp:", chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#f59e0b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
