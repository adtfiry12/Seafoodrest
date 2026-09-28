<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan {{ $nama_bulan }} {{ $tahun }} - Niskala Samudra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
        }

        @media print {
            .no-print { display: none !important; }
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 py-10 px-4 sm:px-6">

    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 shadow-md rounded-2xl print:shadow-none print:p-0 print:rounded-none">

        <!-- Action Buttons (Hidden on Print) -->
        <div class="no-print flex items-center justify-between pb-6 mb-6 border-b border-slate-200">
            <a href="{{ route('admin.laporan.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Laporan
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-charcoal-900 text-white rounded-lg text-sm font-bold hover:bg-charcoal-800 shadow-md transition">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>

        <!-- Kop Laporan Resmi -->
        <div class="border-b-2 border-black pb-4 mb-6 text-center">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-16 w-auto object-contain">
                <div>
                    <h1 class="text-2xl font-bold tracking-wider uppercase">NISKALA SAMUDRA</h1>
                    <p class="text-sm font-sans text-slate-600">Restoran Seafood Premium & Olahan Laut Segar</p>
                </div>
            </div>
            <p class="text-xs font-sans text-slate-500">Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah · Telp: +62 851-9119-4059 · Email: niskalasamudra@gmail.com</p>
        </div>

        <!-- Judul & Periode Laporan -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold uppercase underline">LAPORAN REKAPITULASI PENJUALAN</h2>
            <p class="text-sm font-sans mt-1">Periode: <strong>{{ $nama_bulan }} {{ $tahun }}</strong></p>
            <p class="text-xs font-sans text-slate-500">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB oleh {{ Auth::user()->nama ?? 'Administrator' }}</p>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-2 gap-4 mb-6 font-sans">
            <div class="p-4 border border-black rounded-lg">
                <p class="text-xs text-slate-600 uppercase font-semibold">Total Pendapatan (Lunas)</p>
                <p class="text-xl font-bold mt-1 text-black">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="p-4 border border-black rounded-lg">
                <p class="text-xs text-slate-600 uppercase font-semibold">Total Transaksi Selesai</p>
                <p class="text-xl font-bold mt-1 text-black">{{ $total_pesanan }} Pesanan</p>
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="mb-8 font-sans">
            <table class="w-full text-left text-xs border-collapse border border-black">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="border border-black px-3 py-2 text-center w-10">No</th>
                        <th class="border border-black px-3 py-2">Tanggal</th>
                        <th class="border border-black px-3 py-2">Kode Transaksi</th>
                        <th class="border border-black px-3 py-2">Pelanggan</th>
                        <th class="border border-black px-3 py-2">Tipe</th>
                        <th class="border border-black px-3 py-2">Metode</th>
                        <th class="border border-black px-3 py-2 text-right">Total Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi as $index => $item)
                        <tr>
                            <td class="border border-black px-3 py-2 text-center">{{ $index + 1 }}</td>
                            <td class="border border-black px-3 py-2">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="border border-black px-3 py-2 font-mono font-bold">{{ $item->kode_transaksi }}</td>
                            <td class="border border-black px-3 py-2">{{ $item->pelanggan->nama ?? $item->nama_pelanggan ?? 'Umum' }}</td>
                            <td class="border border-black px-3 py-2">{{ $item->tipe_pesanan }}</td>
                            <td class="border border-black px-3 py-2">{{ $item->metode_pembayaran }}</td>
                            <td class="border border-black px-3 py-2 text-right font-semibold">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border border-black px-3 py-6 text-center text-slate-500">Tidak ada transaksi yang diselesaikan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-100 font-bold">
                        <td colspan="6" class="border border-black px-3 py-2.5 text-right uppercase">Total Penjualan:</td>
                        <td class="border border-black px-3 py-2.5 text-right">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Kolom Tanda Tangan -->
        <div class="font-sans text-xs pt-8 grid grid-cols-2 gap-8 break-inside-avoid">
            <div></div>
            <div class="text-center">
                <p>Jepara, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold mt-1">Penanggung Jawab / Manajer Restoran,</p>
                <div class="h-20"></div>
                <p class="font-bold underline text-sm">{{ Auth::user()->nama ?? 'Aditya Mukti Firmansyah' }}</p>
                <p class="text-slate-600">Niskala Samudra Resto</p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            @if(request()->has('autoprint'))
                window.print();
            @endif
        }
    </script>
</body>
</html>
