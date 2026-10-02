<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $transaksi->kode_transaksi }} - Niskala Samudra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Courier Prime', monospace;
            color: #000;
        }

        .logo-struk {
            width: auto;
            height: 60px;
            margin: 0 auto 10px auto;
            object-fit: contain;
        }

        @media print {
            .no-print { display: none !important; }

            @page {
                margin: 0;
            }

            body {
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }

            #area-struk {
                width: 75mm !important;
                max-width: 75mm !important;
                margin: 0 !important;
                padding: 4mm !important;
                border: none !important;
                box-shadow: none !important;
            }

            .border-gray-400 {
                border-color: #000 !important;
            }

            .logo-struk {
                filter: grayscale(100%) contrast(200%);
                -webkit-filter: grayscale(100%) contrast(200%);
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex justify-center py-10">

    <div id="area-struk" class="bg-white p-6 w-full max-w-xs shadow-lg border border-gray-200">

        <div class="text-center mb-6 no-print">
            <button onclick="window.print()" class="px-4 py-2 bg-charcoal-900 text-white text-xs font-semibold rounded-lg hover:bg-gold-600 transition shadow-xs cursor-pointer">
                Print Struk Manual
            </button>
            <p class="text-[11px] text-gray-400 mt-1.5">Struk akan otomatis dicetak saat halaman dibuka.</p>
        </div>

        <div class="text-center border-b-2 border-dashed border-gray-400 pb-3 mb-3 flex flex-col items-center">

            <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="logo-struk">

            <h1 class="text-lg font-bold uppercase tracking-wider">Niskala Samudra</h1>
            <p class="text-xs mt-1">Jl. Pantai Kartini No. 12, Jepara</p>
            <p class="text-xs">Telp/WA: +62 851-9119-4059</p>
        </div>

        <div class="text-xs mb-3">
            <div class="flex justify-between">
                <span>Tgl: {{ $transaksi->created_at->format('d/m/y H:i') }}</span>
                <span>Ksr: {{ $transaksi->karyawan->nama ?? 'Sistem' }}</span>
            </div>
            <div class="flex justify-between mt-1">
                <span>No: <strong>{{ $transaksi->kode_transaksi }}</strong></span>
                <span>Tipe: {{ strtoupper($transaksi->tipe_pesanan) }}</span>
            </div>
            <div class="mt-1">
                <span>Plgn: {{ $transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan ?? 'Umum (Guest)' }}</span>
            </div>
        </div>

        <div class="border-t-2 border-b-2 border-dashed border-gray-400 py-2 mb-3">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left">
                        <th class="font-normal pb-1 w-1/2">Item</th>
                        <th class="font-normal pb-1 text-center w-1/6">Qty</th>
                        <th class="font-normal pb-1 text-right w-1/3">Sub</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->detail as $dt)
                        <tr>
                            <td class="py-1 align-top pr-1">
                                {{ $dt->menu->nama_menu ?? $dt->nama_menu }} <br>
                                <span class="text-[10px]">@ {{ number_format($dt->harga_satuan, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-1 align-top text-center">{{ $dt->qty }}</td>
                            <td class="py-1 align-top text-right">{{ number_format($dt->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-xs space-y-1 mb-4">
            <div class="flex justify-between font-bold text-sm">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Metode</span>
                <span>{{ $transaksi->metode_pembayaran }}</span>
            </div>
            @if($transaksi->ongkos_kirim > 0)
                <div class="flex justify-between">
                    <span>Ongkos kirim</span>
                    <span>Rp {{ number_format($transaksi->ongkos_kirim, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between">
                <span>Status</span>
                <span class="{{ $transaksi->status_pembayaran == 'Lunas' ? '' : 'font-bold' }}">
                    {{ strtoupper($transaksi->status_pembayaran) }}
                </span>
            </div>
        </div>

        @if($transaksi->catatan)
            <div class="text-xs mb-3 border border-gray-400 p-1">
                <strong>Catatan:</strong> {{ $transaksi->catatan }}
            </div>
        @endif

        <div class="text-center text-xs border-t-2 border-dashed border-gray-400 pt-3 mt-3">
            <p>Terima kasih atas kunjungannya!</p>
            <p>Silakan datang kembali.</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
