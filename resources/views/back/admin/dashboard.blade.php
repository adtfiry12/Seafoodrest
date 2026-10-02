@extends('template.back_admin')

@section('content')

<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Ringkasan Penjualan Restoran -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200/70 font-semibold text-[10px] uppercase tracking-wider">
                    <i class="fa-solid fa-utensils text-[9px] text-amber-600"></i> Niskala Samudra Resto
                </span>
                <span class="text-slate-300">•</span>
                <span class="font-medium text-slate-500 text-xs">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-charcoal-950 tracking-tight">
                Ringkasan Penjualan & Operasional
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">
                Pantau perputaran omzet harian, antrean transaksi masuk, dan performa menu hidangan hari ini.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <div class="bg-white border border-slate-200/90 rounded-xl px-3.5 py-2 shadow-2xs text-right">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Layanan</p>
                <p class="text-sm font-bold font-mono text-charcoal-900 tabular-nums"
                   x-data="{ time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }"
                   x-init="setInterval(() => time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }), 1000)"
                   x-text="time + ' WIB'"></p>
            </div>
            <a href="{{ route('admin.transaksi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-charcoal-950 hover:bg-gold-600 text-white text-xs font-semibold rounded-xl shadow-xs transition active:scale-95">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Catat Transaksi</span>
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="group inline-flex items-center gap-2 px-3.5 py-2.5 bg-white hover:bg-gold-50 hover:text-gold-900 border border-slate-200/90 hover:border-gold-300 text-slate-700 text-xs font-semibold rounded-xl shadow-2xs transition-all duration-200 active:scale-95">
                <i class="fa-solid fa-chart-pie text-xs text-gold-600 group-hover:text-gold-700 transition-colors"></i>
                <span class="hidden sm:inline">Rekap Laporan</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold tracking-tight text-charcoal-950 mt-1.5 tabular-nums">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Antrean Pesanan</p>
                    <h3 class="text-2xl font-bold tracking-tight text-charcoal-950 mt-1.5 tabular-nums">{{ $pesananBaru }} <span class="text-xs text-slate-500 font-normal">Perlu Konfirmasi</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Menu Hidangan</p>
                    <h3 class="text-2xl font-bold tracking-tight text-charcoal-950 mt-1.5 tabular-nums">{{ $totalMenu }} <span class="text-xs text-slate-500 font-normal">Varian Aktif</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-utensils"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Pelanggan</p>
                    <h3 class="text-2xl font-bold tracking-tight text-charcoal-950 mt-1.5 tabular-nums">{{ $totalPelanggan }} <span class="text-xs text-slate-500 font-normal">Akun Terdaftar</span></h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
            <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3.5">
                <h3 class="font-semibold text-charcoal-900 text-sm sm:text-base">Tren Penjualan 7 Hari Terakhir</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-1 bg-white border border-slate-200/80 rounded-2xl p-5 sm:p-6 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
            <h3 class="font-semibold text-charcoal-900 text-sm sm:text-base border-b border-slate-100 pb-3.5 mb-4">Menu Paling Diminati</h3>
            <div class="space-y-3.5">
                @forelse($topMenus as $top)
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl bg-slate-100 border border-slate-200/80 overflow-hidden shrink-0">
                            @if($top->menu && $top->menu->gambar)
                                <img src="{{ asset('storage/' . $top->menu->gambar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center w-full h-full text-slate-400 text-xs"><i class="fa-solid fa-image"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-xs sm:text-sm text-charcoal-900 truncate">{{ $top->menu->nama_menu ?? 'Menu Terhapus' }}</h4>
                            <p class="text-[11px] text-slate-500">{{ $top->total_terjual }} Porsi Terjual</p>
                        </div>
                        <div class="text-right shrink-0">
                            @if($top->menu)
                                <span class="text-xs font-semibold text-gold-700">Rp {{ number_format($top->menu->harga, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-xs sm:text-sm">Belum ada data penjualan.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.04)] overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-semibold text-charcoal-900 text-sm sm:text-base">Transaksi Terkini</h3>
            <a href="{{ route('admin.transaksi.index') }}" class="text-xs font-semibold text-gold-700 hover:text-gold-800 transition">Semua Transaksi &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm text-slate-600">
                <thead class="bg-slate-50/70 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3">Invoice</th>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerbaru as $trx)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 font-mono font-semibold text-charcoal-900 text-xs">{{ $trx->kode_transaksi }}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-medium text-charcoal-900 block">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $trx->tipe_pesanan }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-gold-700">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5">
                                @if($trx->status_pesanan == 'Menunggu ACC')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-50 text-rose-700 border border-rose-200/60">Menunggu</span>
                                @elseif($trx->status_pesanan == 'Selesai')
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">Selesai</span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-medium bg-sky-50 text-sky-700 border border-sky-200/60">{{ $trx->status_pesanan }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.transaksi.show', $trx->kode_transaksi) }}" title="Lihat detail transaksi" aria-label="Lihat detail transaksi" class="group inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-50 hover:bg-charcoal-950 text-slate-500 hover:text-gold-400 border border-slate-200/80 hover:border-charcoal-950 transition-all duration-200 text-xs shadow-2xs active:scale-95">
                                    <i class="fa-solid fa-eye group-hover:scale-110 transition-transform"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    (function() {
        function initSalesChart() {
            const canvas = document.getElementById('salesChart');
            if (!canvas) return;

            if (typeof Chart === 'undefined') {
                setTimeout(initSalesChart, 100);
                return;
            }

            // Clean up existing instance if already rendered on canvas
            if (typeof Chart.getChart === 'function') {
                const existing = Chart.getChart(canvas);
                if (existing) {
                    existing.destroy();
                }
            }

            const ctx = canvas.getContext('2d');
            const chartLabels = {!! json_encode($chartDates ?? []) !!};
            const chartData = {!! json_encode($chartTotals ?? [], JSON_NUMERIC_CHECK) !!};

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
                        pointHoverRadius: 6,
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
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSalesChart);
        } else {
            initSalesChart();
        }

        // Auto-refresh dashboard setiap 60 detik
        if (!window.__adminDashboardRefreshTimer) {
            window.__adminDashboardRefreshTimer = setInterval(function() {
                if (window.location.pathname.startsWith('/admin/dashboard') && typeof window.pjaxNavigate === 'function') {
                    window.pjaxNavigate(window.location.href, false);
                }
            }, 60000);
        }
    })();
</script>
@endsection
