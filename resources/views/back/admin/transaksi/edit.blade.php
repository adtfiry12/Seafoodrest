@extends('template.back_admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0 border border-gold-200/50 shadow-2xs">
                <i class="fa-solid fa-file-pen text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Update Status Transaksi</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui progres pesanan dapur, status pembayaran, atau penugasan kurir.</p>
            </div>
        </div>
        <a href="{{ route('admin.transaksi.show', $transaksi->kode_transaksi) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-charcoal-950 transition self-start sm:self-center px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 shadow-2xs">
            <i class="fa-solid fa-arrow-left text-xs text-gold-600"></i>
            <span>Kembali ke Detail</span>
        </a>
    </div>

    @if($errors->any() || session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                <div class="flex-1">
                    <p class="font-bold text-rose-950">Gagal Memperbarui Status:</p>
                    @if(session('error'))
                        <p class="mt-0.5">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Kartu Ringkasan Informasi Transaksi -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-100">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Nomor Invoice</span>
                <p class="font-mono font-bold text-base sm:text-lg text-charcoal-950">{{ $transaksi->kode_transaksi }}</p>
            </div>
            <div class="sm:text-right">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Waktu Pemesanan</span>
                <p class="text-xs sm:text-sm font-medium text-slate-600">{{ $transaksi->created_at->translatedFormat('d M Y · H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 pt-1">
            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Pemesan</p>
                <p class="text-sm font-bold text-charcoal-950 truncate mt-0.5">{{ $transaksi->nama_pelanggan }}</p>
                <p class="text-xs text-slate-500 truncate">{{ $transaksi->no_hp_pelanggan ?? 'Tanpa kontak' }}</p>
            </div>

            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tipe Layanan</p>
                <div class="mt-1">
                    @if($transaksi->tipe_pesanan === 'Dine-In')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            <i class="fa-solid fa-utensils text-[10px]"></i> Dine-In
                        </span>
                    @elseif($transaksi->tipe_pesanan === 'Takeaway')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">
                            <i class="fa-solid fa-bag-shopping text-[10px]"></i> Takeaway
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-bold bg-sky-50 text-sky-800 border border-sky-200">
                            <i class="fa-solid fa-motorcycle text-[10px]"></i> Delivery
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Metode Bayar</p>
                <p class="text-sm font-bold text-charcoal-950 mt-0.5">{{ $transaksi->metode_pembayaran }}</p>
                <p class="text-xs text-slate-500 font-mono font-medium">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
            </div>

            <div class="bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Status Aktif</p>
                <div class="mt-1 flex flex-col gap-1">
                    <span class="text-xs font-bold {{ $transaksi->status_pembayaran === 'Lunas' ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $transaksi->status_pembayaran }}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-500">
                        {{ $transaksi->status_pesanan }}
                    </span>
                </div>
            </div>
        </div>

        @if($transaksi->tipe_pesanan === 'Delivery' && $transaksi->alamat)
            <div class="rounded-xl bg-sky-50/60 border border-sky-100 p-3 text-xs text-sky-950 flex items-start gap-2.5">
                <i class="fa-solid fa-location-dot text-sky-600 mt-0.5 shrink-0"></i>
                <div class="min-w-0">
                    <span class="font-bold">Alamat Pengantaran:</span>
                    <p class="text-slate-700 mt-0.5 leading-relaxed">{{ $transaksi->alamat }}</p>
                    @if($transaksi->patokan_alamat)
                        <p class="text-slate-500 mt-0.5 italic">Patokan: {{ $transaksi->patokan_alamat }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Form Kontrol Status -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-8 shadow-xs">
        <form action="{{ route('admin.transaksi.update', $transaksi->kode_transaksi) }}" method="POST" @submit.prevent="openActionModal($event, 'Konfirmasi Perubahan Status', 'Yakin ingin menyimpan perubahan status transaksi invoice {{ $transaksi->kode_transaksi }}?')" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                <!-- Dropdown Status Pembayaran -->
                <div>
                    <label for="status_pembayaran" class="block text-sm font-semibold text-charcoal-900 mb-2">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-gold-600 text-xs"></i>
                            <span>Status Pembayaran</span>
                            <span class="text-rose-500">*</span>
                        </span>
                    </label>
                    @php
                        $paymentSelectOptions = collect($statusPembayaranOptions)->map(function($status) {
                            $label = match($status) {
                                'Lunas' => 'Lunas (Pembayaran Telah Diterima)',
                                'Belum Lunas' => 'Belum Lunas (Menunggu Pembayaran)',
                                'Menunggu Konfirmasi' => 'Menunggu Konfirmasi (Cek Bukti QRIS)',
                                'Batal' => 'Batal (Transaksi Dibatalkan)',
                                default => $status
                            };
                            return ['id' => $status, 'label' => $label];
                        })->values();
                    @endphp
                    <div x-data="customSimpleSelect(@js($paymentSelectOptions), @js(old('status_pembayaran', $transaksi->status_pembayaran)), '-- Pilih Status Pembayaran --')" @click.outside="open = false" class="relative">
                        <input type="hidden" name="status_pembayaran" :value="selected" required>
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-2xs">
                            <span x-text="selectedLabel()" class="font-medium text-charcoal-900"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl p-1">
                            <template x-for="option in options" :key="option.id">
                                <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 transition-colors" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'hover:bg-gold-50 hover:text-gold-700'">
                                    <span x-text="option.label"></span>
                                    <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('status_pembayaran')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dropdown Status Pesanan -->
                <div>
                    <label for="status_pesanan" class="block text-sm font-semibold text-charcoal-900 mb-2">
                        <span class="flex items-center gap-2">
                            <i class="fa-solid fa-utensils text-gold-600 text-xs"></i>
                            <span>Progress Pesanan</span>
                            <span class="text-rose-500">*</span>
                        </span>
                    </label>
                    @php
                        $orderStatusSelectOptions = collect($statusPesananOptions)->map(function($status) {
                            return ['id' => $status, 'label' => $status];
                        })->values();
                    @endphp
                    <div x-data="customSimpleSelect(@js($orderStatusSelectOptions), @js(old('status_pesanan', $transaksi->status_pesanan)), '-- Pilih Progress Pesanan --')" @click.outside="open = false" class="relative">
                        <input type="hidden" name="status_pesanan" :value="selected" required>
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-2xs">
                            <span x-text="selectedLabel()" class="font-medium text-charcoal-900"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl p-1">
                            <template x-for="option in options" :key="option.id">
                                <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 transition-colors" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'hover:bg-gold-50 hover:text-gold-700'">
                                    <span x-text="option.label"></span>
                                    <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-[11px] text-slate-400"></i>
                        <span>Pilihan progress dibatasi berdasarkan alur logis pesanan restoran agar konsisten.</span>
                    </p>
                    @error('status_pesanan')
                        <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penugasan Kurir (Khusus Tipe Delivery) -->
                @if($transaksi->tipe_pesanan === 'Delivery')
                    <div class="pt-4 border-t border-slate-100">
                        <label for="kurir_id" class="block text-sm font-semibold text-charcoal-900 mb-2">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-motorcycle text-sky-600 text-xs"></i>
                                <span>Kurir Pengantar (Khusus Delivery)</span>
                            </span>
                        </label>
                        @php
                            $kurirSelectOptions = collect([['id' => '', 'label' => '-- Belum Ditentukan / Kurir Ambil Mandiri --']])
                                ->concat($kurirList->map(fn($k) => ['id' => (string) $k->id, 'label' => $k->nama . ($k->no_hp ? ' ('.$k->no_hp.')' : '')]))
                                ->values();
                        @endphp
                        <div x-data="customSimpleSelect(@js($kurirSelectOptions), @js((string) old('kurir_id', $transaksi->kurir_id ?? '')), '-- Belum Ditentukan / Kurir Ambil Mandiri --')" @click.outside="open = false" class="relative">
                            <input type="hidden" name="kurir_id" :value="selected">
                            <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-2xs">
                                <span x-text="selectedLabel()" class="font-medium text-charcoal-900"></span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl p-1 max-h-60 overflow-y-auto custom-scrollbar">
                                <template x-for="option in options" :key="option.id">
                                    <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 transition-colors" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'hover:bg-gold-50 hover:text-gold-700'">
                                        <span x-text="option.label"></span>
                                        <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">
                            Admin dapat menugaskan kurir spesifik secara manual atau membiarkan kurir mengambil pesanan di panel operasional mereka.
                        </p>
                        @error('kurir_id')
                            <p class="text-xs text-rose-500 mt-2 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Tombol Simpan & Batal -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.transaksi.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-xs sm:text-sm font-semibold text-center text-slate-600 bg-white border border-slate-300/80 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition shadow-2xs">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs sm:text-sm font-bold text-charcoal-950 bg-gold-500 hover:bg-gold-600 active:bg-gold-700 rounded-xl shadow-xs active:scale-95 transition-all duration-200">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Perubahan Status</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
