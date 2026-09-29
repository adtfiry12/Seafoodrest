@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
     x-data="{
         completeModalOpen: false,
         actionUrl: '',
         kodeTrx: '',
         penerimaNama: '',
         isCod: false,
         totalTagihan: '',
         openCompleteModal(url, kode, nama, isCod, tagihan) {
             this.actionUrl = url;
             this.kodeTrx = kode;
             this.penerimaNama = nama;
             this.isCod = isCod;
             this.totalTagihan = tagihan;
             this.completeModalOpen = true;
         }
     }">

    <!-- Header Pos Kurir -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0 border border-blue-100">
                <i class="fa-solid fa-motorcycle text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pengantaran (Delivery)</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola pesanan siap antar, rute pelanggan, hingga konfirmasi serah terima.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('karyawan.delivery') }}" method="GET" class="w-full sm:w-64 relative">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / pelanggan / alamat..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-xs sm:text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-xs">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <button onclick="window.location.reload();" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-gold-600 transition shadow-xs flex items-center justify-center gap-2">
                <i class="fa-solid fa-rotate-right"></i> <span>Refresh</span>
            </button>
        </div>
    </div>

    <!-- Filter Status Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
        <a href="{{ route('karyawan.delivery', array_filter(['search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-charcoal-900 text-gold-400 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Semua ({{ $countSiap + $countDiantar }})
        </a>
        <a href="{{ route('karyawan.delivery', array_filter(['status' => 'Siap Diantar', 'search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ request('status') === 'Siap Diantar' ? 'bg-charcoal-900 text-gold-400 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Siap Diantar ({{ $countSiap }})
        </a>
        <a href="{{ route('karyawan.delivery', array_filter(['status' => 'Diantar', 'search' => request('search')])) }}"
           class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition whitespace-nowrap {{ request('status') === 'Diantar' ? 'bg-charcoal-900 text-gold-400 shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            Sedang Jalan ({{ $countDiantar }})
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-2.5">
            <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Kanban Grid Kurir -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($transaksi as $trx)
            @php
                $isSiap = $trx->status_pesanan === 'Siap Diantar';
                $isCod = $trx->metode_pembayaran === 'COD' && $trx->status_pembayaran !== 'Lunas';
                $phoneClean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $trx->no_hp_pelanggan));
            @endphp
            <div class="bg-white border-2 border-slate-200 rounded-2xl shadow-xs overflow-hidden flex flex-col justify-between transition hover:shadow-md relative">

                <!-- Indicator Bar Atas -->
                <div class="absolute top-0 left-0 w-full h-1.5 {{ $isSiap ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-blue-500 to-indigo-600' }}"></div>

                <div>
                    <!-- Header Card -->
                    <div class="p-4 border-b border-slate-100 flex justify-between items-start bg-slate-50/70">
                        <div>
                            <h3 class="font-mono font-bold text-charcoal-900 text-base sm:text-lg leading-tight">{{ $trx->kode_transaksi }}</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i> Pesanan masuk: {{ $trx->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            @if($isSiap)
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-lg uppercase tracking-wider">
                                    <i class="fa-solid fa-box mr-1"></i> Siap Diantar
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-bold rounded-lg uppercase tracking-wider animate-pulse">
                                    <i class="fa-solid fa-motorcycle mr-1"></i> Sedang Jalan
                                </span>
                            @endif

                            @if($trx->status_pembayaran == 'Lunas')
                                <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-bold rounded uppercase tracking-wider">
                                    <i class="fa-solid fa-check mr-1"></i> Lunas
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-bold rounded uppercase tracking-wider">
                                    <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Tagih (COD)
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-4 sm:p-5 space-y-3.5">
                        <!-- Penerima & Link Detail -->
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Penerima</p>
                                <p class="font-bold text-charcoal-900 text-sm truncate">{{ $trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Guest' }}</p>
                                <p class="text-xs text-slate-500 font-mono mt-0.5"><i class="fa-solid fa-phone text-[10px] text-slate-400 mr-1"></i> {{ $trx->no_hp_pelanggan }}</p>
                            </div>
                            <a href="{{ route('karyawan.pesanan.detail', $trx->kode_transaksi) }}" title="Lihat detail pesanan" aria-label="Lihat detail pesanan" class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-xl text-slate-400 hover:text-charcoal-900 hover:bg-slate-100 transition border border-slate-200">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </div>

                        <!-- Alamat & Patokan Lengkap -->
                        <div class="rounded-xl bg-blue-50/70 border border-blue-100 p-3 text-xs">
                            <p class="font-bold text-blue-700 uppercase tracking-wider text-[10px] flex items-center gap-1">
                                <i class="fa-solid fa-location-dot text-blue-600"></i> Alamat Pengantaran:
                            </p>
                            <p class="mt-1 font-medium text-charcoal-900 leading-relaxed">{{ $trx->alamat ?: 'Alamat tidak dicantumkan' }}</p>

                            @if($trx->patokan_alamat)
                                <div class="mt-2 pt-2 border-t border-blue-100/80 text-[11px] text-blue-900 flex items-start gap-1.5">
                                    <i class="fa-solid fa-map-pin text-gold-600 mt-0.5 shrink-0"></i>
                                    <span><strong>Patokan:</strong> {{ $trx->patokan_alamat }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Operasional Kurir (Maps & WA) -->
                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($trx->alamat) }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 text-xs font-bold transition shadow-2xs">
                                <i class="fa-solid fa-location-arrow text-xs text-blue-600 group-hover:text-white"></i>
                                <span>Rute Maps</span>
                            </a>

                            <a href="https://wa.me/{{ $phoneClean }}?text={{ urlencode('Halo kak, saya kurir Niskala Samudra ingin mengantarkan pesanan ' . $trx->kode_transaksi . ' ke alamat kakak. Apakah kakak berada di lokasi?') }}"
                               target="_blank"
                               rel="noopener"
                               class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-600 hover:text-white text-emerald-700 text-xs font-bold transition shadow-2xs">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Chat WA</span>
                            </a>
                        </div>

                        <!-- Status Kurir (Jika sudah berjalan) -->
                        @if(!$isSiap)
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <span class="text-slate-400 block font-semibold uppercase text-[9px] tracking-wider">Kurir Bertugas</span>
                                <div class="flex items-center justify-between mt-1">
                                    <strong class="text-charcoal-900 flex items-center gap-1.5">
                                        <i class="fa-solid fa-user-tag text-gold-600 text-xs"></i>
                                        {{ $trx->kurir->nama ?? 'Karyawan' }}
                                    </strong>
                                    <span class="text-slate-500 font-mono text-[11px]">
                                        <i class="fa-regular fa-clock mr-0.5"></i> Berangkat {{ $trx->waktu_berangkat?->format('H:i') ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Ringkasan Menu & Total -->
                        <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100">
                            <span class="text-slate-500 font-medium">{{ $trx->detail->sum('qty') }} Menu Hidangan</span>
                            <span class="font-bold text-gold-600 text-sm">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Card (Aksi Status) -->
                <div class="p-4 bg-slate-50/80 border-t border-slate-100">
                    @if($isSiap)
                        <!-- Form Mulai Antar (Menugaskan Kurir) -->
                        <form action="{{ route('karyawan.update_status', $trx->kode_transaksi) }}" method="POST"
                              @submit.prevent="openActionModal($event, 'Ambil Pesanan & Mulai Antar', 'Anda akan tercatat sebagai kurir untuk pesanan {{ $trx->kode_transaksi }}. Pesanan sudah siap dibawa?')">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="Diantar">

                            <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                                <i class="fa-solid fa-motorcycle text-sm"></i>
                                <span>Ambil & Mulai Antar</span>
                            </button>
                        </form>
                    @else
                        <!-- Tombol Selesaikan Pengantaran (Buka Modal POD) -->
                        @if($isCod)
                            <div class="flex items-center justify-between mb-2.5 px-3 py-1.5 bg-rose-50 border border-rose-200 rounded-xl">
                                <span class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Wajib Tagih COD:</span>
                                <span class="font-bold text-rose-700 text-sm">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <button type="button"
                                @click="openCompleteModal(
                                    '{{ route('karyawan.update_status', $trx->kode_transaksi) }}',
                                    '{{ $trx->kode_transaksi }}',
                                    '{{ addslashes($trx->pelanggan->nama ?? $trx->nama_pelanggan ?? 'Pelanggan') }}',
                                    {{ $isCod ? 'true' : 'false' }},
                                    '{{ number_format($trx->total_bayar, 0, ',', '.') }}'
                                )"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                            <i class="fa-solid fa-flag-checkered text-sm"></i>
                            <span>Selesaikan Pengantaran</span>
                        </button>
                    @endif
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-xs border border-slate-100">
                <div class="w-20 h-20 mx-auto rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-3xl mb-4">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
                <h3 class="text-xl font-serif font-bold text-charcoal-900 mb-1.5">Tidak Ada Antrean Delivery</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto">
                    @if(request('status'))
                        Tidak ada pesanan delivery dengan status "{{ request('status') }}".
                    @else
                        Semua hidangan pesan antar telah diserahkan kepada pelanggan.
                    @endif
                </p>
                @if(request('status') || request('search'))
                    <a href="{{ route('karyawan.delivery') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-charcoal-900 text-white text-xs font-bold rounded-xl hover:bg-gold-600 transition">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($transaksi->hasPages())
        <div class="mt-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-xs flex justify-center">
            {{ $transaksi->links() }}
        </div>
    @endif

    <!-- Modal Konfirmasi Penyelesaian Pengantaran (Proof of Delivery) -->
    <div x-show="completeModalOpen" x-cloak class="fixed inset-0 z-[70] flex min-h-screen items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="complete-modal-title">
        <div x-show="completeModalOpen" x-transition class="w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">

            <div class="flex items-start justify-between border-b border-slate-100 px-6 py-5 bg-charcoal-950 text-white">
                <div>
                    <span class="text-gold-400 font-bold uppercase tracking-wider text-[10px]">Konfirmasi Serah Terima</span>
                    <h2 id="complete-modal-title" class="text-lg font-serif font-bold text-white mt-0.5">Selesaikan Pengantaran</h2>
                    <p class="text-xs text-slate-300 font-mono" x-text="kodeTrx"></p>
                </div>
                <button type="button" @click="completeModalOpen = false" class="text-slate-400 hover:text-white transition p-1" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="status_pesanan" value="Selesai">
                <template x-if="isCod">
                    <input type="hidden" name="status_pembayaran" value="Lunas">
                </template>

                <div class="p-6 space-y-4">
                    <!-- COD Alert Box -->
                    <div x-show="isCod" class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                        <div class="flex items-center gap-1.5 font-bold text-amber-800">
                            <i class="fa-solid fa-circle-exclamation text-amber-600"></i>
                            <span>Tagihan Tunai (COD) Wajib Diterima:</span>
                        </div>
                        <p class="text-base font-bold text-charcoal-950">Rp <span x-text="totalTagihan"></span></p>
                        <p class="text-[11px] text-amber-700">Pastikan uang tunai dari pelanggan sudah Anda terima penuh sebelum menyelesaikan pesanan.</p>
                    </div>

                    <!-- Input Nama Penerima -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal-900 uppercase tracking-wider mb-1.5">
                            Nama Penerima di Lokasi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="diterima_oleh"
                               required
                               :placeholder="'Contoh: ' + penerimaNama + ' Sendiri / Istri / Satpam'"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-charcoal-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition">
                        <span class="text-[11px] text-slate-400 mt-1 block">Tulis siapa yang menerima makanan secara langsung di alamat tujuan.</span>
                    </div>

                    <!-- Input Foto Bukti Pengantaran (POD) -->
                    <div>
                        <label class="block text-xs font-bold text-charcoal-900 uppercase tracking-wider mb-1.5">
                            Foto Bukti Serah Terima (Opsional)
                        </label>
                        <input type="file"
                               name="bukti_pengantaran"
                               accept="image/*"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 file:mr-3 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition">
                        <span class="text-[11px] text-slate-400 mt-1 block">Unggah foto saat pesanan diserahkan di depan rumah / pelanggan.</span>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <button type="button" @click="completeModalOpen = false" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold transition shadow-xs flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Konfirmasi Selesai</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Auto-refresh setiap 30 detik untuk memantau pesanan baru siap antar
    setTimeout(function() {
        if (!document.querySelector('[aria-modal="true"]:not([style*="display: none"])')) {
            window.location.reload();
        }
    }, 30000);
</script>
@endsection
