@extends('template.back_karyawan')

@section('content')
@php
    $pelangganOptions = collect([
        ['id' => '', 'nama' => '', 'no_hp' => '', 'label' => '-- Pelanggan Umum (Guest / Walk-In) --']
    ])->merge(
        $pelanggan->map(fn($p) => [
            'id' => (string) $p->id,
            'nama' => $p->nama,
            'no_hp' => $p->no_hp ?? '',
            'label' => $p->nama . ($p->no_hp ? ' (' . $p->no_hp . ')' : '')
        ])
    )->values();
@endphp

<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
     x-data="karyawanPosSystem(@js($menu), @js($pelangganOptions), @js($kategoris))">

    <!-- Header POS Kasir -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3.5">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-700 rounded-2xl shrink-0 border border-gold-200 shadow-2xs">
                <i class="fa-solid fa-cash-register text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-950">Kasir</h1>
                <p class="text-xs text-slate-500 font-medium">Layanan pesanan langsung tamu restoran</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" @click="resetCart()" x-show="cart.length > 0" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition shadow-2xs active:scale-95">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Kosongkan Keranjang</span>
            </button>
            <a href="{{ route('karyawan.pesanan_baru') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 bg-white border border-slate-200/90 rounded-xl hover:bg-slate-50 transition shadow-2xs">
                <i class="fa-solid fa-arrow-left text-xs text-slate-400"></i>
                <span>Antrean Pesanan</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any() || session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs sm:text-sm text-rose-900 shadow-xs">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                <div class="flex-1">
                    <p class="font-bold text-rose-950">Gagal Menyimpan Transaksi:</p>
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

    <form action="{{ route('karyawan.keranjang.store') }}" method="POST" id="form-pos" @submit="handleSubmit($event)">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- KOLOM KIRI (Detail Pemesan, Pembayaran & Ringkasan Tagihan) 5/12 -->
            <div class="lg:col-span-5 space-y-5">

                <!-- Card 1: Data Pelanggan & Meja -->
                <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="font-bold text-sm text-charcoal-950 flex items-center gap-2">
                            <i class="fa-solid fa-user-tag text-gold-600"></i>
                            <span>Data Tamu & Lokasi Meja</span>
                        </h2>
                        <span class="text-[11px] text-slate-400 font-medium">Langkah 1</span>
                    </div>

                    <!-- Searchable Pelanggan Select -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pelanggan Terdaftar</label>
                        <div @click.outside="pelangganDropdownOpen = false" class="relative">
                            <input type="hidden" name="pengguna_id" :value="selectedPelanggan">
                            <button type="button" @click="pelangganDropdownOpen = !pelangganDropdownOpen"
                                    class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50/70 px-3.5 py-2.5 text-left text-xs sm:text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20">
                                <span class="truncate" x-text="selectedPelangganLabel() || '-- Pelanggan Umum (Guest / Walk-In) --'"
                                      :class="selectedPelanggan ? 'text-charcoal-950 font-semibold' : 'text-slate-500'"></span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': pelangganDropdownOpen }"></i>
                            </button>

                            <div x-show="pelangganDropdownOpen" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                                <div class="border-b border-slate-100 p-2 bg-slate-50/70">
                                    <div class="relative">
                                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                                        <input type="search" x-model="pelangganSearch" placeholder="Ketik nama atau no. HP..." class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-8 pr-3 text-xs text-charcoal-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none">
                                    </div>
                                </div>
                                <div class="max-h-48 overflow-y-auto p-1 custom-scrollbar divide-y divide-slate-50">
                                    <button type="button" x-show="filteredPelanggan().length === 0" class="w-full px-3 py-2.5 text-center text-xs text-slate-400" disabled>Tidak ada pelanggan cocok.</button>
                                    <template x-for="p in filteredPelanggan()" :key="p.id">
                                        <button type="button" @click="selectPelanggan(p)" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-xs text-charcoal-900 hover:bg-gold-50 hover:text-gold-900 transition">
                                            <div class="truncate">
                                                <span class="font-semibold" x-text="p.nama || p.label"></span>
                                                <span x-show="p.no_hp" class="text-[11px] text-slate-400 ml-1.5" x-text="'(' + p.no_hp + ')'"></span>
                                            </div>
                                            <i x-show="selectedPelanggan == String(p.id)" class="fa-solid fa-check text-gold-600 shrink-0 ml-2 text-xs"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nama & No. HP -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="nama_pelanggan" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Pemesan</label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" x-model="namaPelanggan" placeholder="Nama tamu walk-in..." class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                        <div>
                            <label for="no_hp_pelanggan" class="block text-xs font-semibold text-slate-700 mb-1.5">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" x-model="noHpPelanggan" placeholder="Cth: 08123456789" class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                    </div>

                    <!-- Tipe Pesanan Segmented -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Layanan Pesanan</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center"
                                   :class="tipePesanan === 'Dine-In' ? 'bg-gold-50/70 border-gold-400 text-gold-950 font-bold ring-1 ring-gold-400/30' : 'bg-slate-50/60 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <input type="radio" name="tipe_pesanan" value="Dine-In" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-utensils text-sm mb-1"></i>
                                <span class="text-xs">Dine-In</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center"
                                   :class="tipePesanan === 'Takeaway' ? 'bg-gold-50/70 border-gold-400 text-gold-950 font-bold ring-1 ring-gold-400/30' : 'bg-slate-50/60 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <input type="radio" name="tipe_pesanan" value="Takeaway" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-bag-shopping text-sm mb-1"></i>
                                <span class="text-xs">Takeaway</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-2.5 rounded-xl border cursor-pointer transition text-center"
                                   :class="tipePesanan === 'Delivery' ? 'bg-gold-50/70 border-gold-400 text-gold-950 font-bold ring-1 ring-gold-400/30' : 'bg-slate-50/60 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <input type="radio" name="tipe_pesanan" value="Delivery" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-motorcycle text-sm mb-1"></i>
                                <span class="text-xs">Delivery</span>
                            </label>
                        </div>
                    </div>

                    <!-- Nomor Meja (jika Dine-In) -->
                    <div x-show="tipePesanan === 'Dine-In'" x-transition class="pt-1">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Meja Tamu</label>
                        <div class="relative">
                            <i class="fa-solid fa-chair absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="nomor_meja" placeholder="Contoh: Meja 05, Meja VIP 1..." class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl pl-9 pr-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                    </div>

                    <!-- Alamat Delivery (jika Delivery) -->
                    <div x-show="tipePesanan === 'Delivery'" x-transition class="space-y-3 pt-1">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Pengantaran <span class="text-red-500">*</span></label>
                            <textarea name="alamat" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl p-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Patokan Alamat (Opsional)</label>
                            <input type="text" name="patokan_alamat" placeholder="Cth: Depan gapura, pagar cokelat..." class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl px-3.5 py-2 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                        </div>
                    </div>
                </div>

                <!-- Card 2: Metode Bayar & Alur Pesanan -->
                <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="font-bold text-sm text-charcoal-950 flex items-center gap-2">
                            <i class="fa-solid fa-credit-card text-gold-600"></i>
                            <span>Pembayaran & Alur Dapur</span>
                        </h2>
                        <span class="text-[11px] text-slate-400 font-medium">Langkah 2</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <!-- Metode Bayar -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Metode Bayar</label>
                            <select name="metode_pembayaran" x-model="metodePembayaran" class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20">
                                <option value="Tunai">Tunai (Bayar di Kasir)</option>
                                <option value="QRIS">QRIS / Transfer Bank</option>
                                <option value="COD" x-show="tipePesanan === 'Delivery'">Bayar di Tempat (COD)</option>
                            </select>
                        </div>

                        <!-- Status Bayar -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status Pembayaran</label>
                            <select name="status_pembayaran" x-model="statusPembayaran" class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 text-xs sm:text-sm rounded-xl px-3 py-2.5 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"
                                    :class="statusPembayaran === 'Lunas' ? 'border-emerald-300 text-emerald-800 font-semibold' : 'border-amber-300 text-amber-800 font-semibold'">
                                <option value="Lunas">Lunas (Sudah Bayar)</option>
                                <option value="Belum Lunas">Belum Lunas (Bayar Nanti)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status Alur Pesanan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alur Pesanan Setelah Disimpan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs transition"
                                   :class="statusPesanan === 'Dimasak' ? 'bg-orange-50/70 border-orange-300 text-orange-950 font-semibold' : 'bg-slate-50/50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <input type="radio" name="status_pesanan" value="Dimasak" x-model="statusPesanan" class="text-orange-600">
                                <div class="truncate">
                                    <p class="font-bold">Langsung Dapur</p>
                                    <p class="text-[10px] text-slate-500">Status Dimasak</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 border rounded-xl cursor-pointer text-xs transition"
                                   :class="statusPesanan === 'Menunggu ACC' ? 'bg-amber-50/70 border-amber-300 text-amber-950 font-semibold' : 'bg-slate-50/50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <input type="radio" name="status_pesanan" value="Menunggu ACC" x-model="statusPesanan" class="text-amber-600">
                                <div class="truncate">
                                    <p class="font-bold">Antrean Kasir</p>
                                    <p class="text-[10px] text-slate-500">Menunggu ACC</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Catatan Dapur / Pesanan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Cth: Sambal dipisah, jangan pakai bawang goreng..." class="w-full bg-slate-50/70 border border-slate-200 text-charcoal-900 rounded-xl p-2.5 text-xs focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"></textarea>
                    </div>
                </div>

                <!-- Card 3: Tagihan & Tombol Submit (Sticky) -->
                <div class="bg-charcoal-950 border border-charcoal-800 text-white rounded-2xl sm:rounded-3xl p-5 shadow-lg space-y-4">
                    <div class="flex justify-between items-center border-b border-charcoal-800 pb-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gold-400">Ringkasan Tagihan</span>
                        <span class="text-xs font-mono text-slate-400" x-text="cart.length + ' Menu Dipesan'"></span>
                    </div>

                    <div class="space-y-2 text-xs sm:text-sm text-slate-300">
                        <div class="flex justify-between">
                            <span>Subtotal Menu</span>
                            <span class="font-mono font-bold text-white tabular-nums" x-text="formatRupiah(subtotal())"></span>
                        </div>
                        <div x-show="tipePesanan === 'Delivery'" class="flex justify-between text-slate-300" x-cloak>
                            <span>Ongkos Kirim Flat</span>
                            <span class="font-mono font-bold text-white tabular-nums" x-text="formatRupiah(ongkir)"></span>
                        </div>
                        <div class="border-t border-charcoal-800 pt-3 flex justify-between items-baseline">
                            <span class="font-bold text-white text-sm">Total Bayar</span>
                            <span class="font-serif font-bold text-gold-400 text-xl sm:text-2xl tabular-nums" x-text="formatRupiah(totalAkhir())"></span>
                        </div>
                    </div>

                    <button type="submit" :disabled="cart.length === 0"
                            class="w-full py-3.5 bg-gold-500 hover:bg-gold-600 disabled:bg-charcoal-800 disabled:text-slate-500 disabled:cursor-not-allowed text-charcoal-950 font-bold text-xs sm:text-sm rounded-xl transition shadow-xs flex items-center justify-center gap-2 active:scale-95">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Simpan & Proses Transaksi</span>
                    </button>
                    <p x-show="cart.length === 0" class="text-center text-[11px] text-slate-400">Tambahkan minimal 1 menu seafood di kolom kanan.</p>
                </div>

            </div>

            <!-- KOLOM KANAN (Keranjang Aktif & Pemilihan Menu Seafood) 7/12 -->
            <div class="lg:col-span-7 space-y-5">

                <!-- Card Keranjang Aktif Kasir -->
                <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-gold-50 text-gold-700 border border-gold-100 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </div>
                            <h2 class="font-bold text-sm sm:text-base text-charcoal-950">Keranjang Menu Kasir</h2>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700" x-text="cartTotalPorsi() + ' Porsi'"></span>
                    </div>

                    <!-- Keranjang Kosong State -->
                    <div x-show="cart.length === 0" class="py-8 text-center text-slate-400">
                        <i class="fa-solid fa-utensils text-3xl mb-2 text-slate-300"></i>
                        <p class="text-xs sm:text-sm font-medium text-slate-600">Keranjang kasir masih kosong.</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Cari menu via dropdown di bawah atau klik kartu menu seafood.</p>
                    </div>

                    <!-- List Item Keranjang -->
                    <div class="space-y-2.5 max-h-[34rem] sm:max-h-[22rem] overflow-y-auto pr-1 custom-scrollbar" x-show="cart.length > 0">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/70 hover:bg-slate-50 transition gap-2 sm:gap-4">
                                <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.id">
                                <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">

                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="w-11 h-11 rounded-lg overflow-hidden shrink-0 border border-slate-200 bg-white">
                                        <img :src="item.image" :alt="item.nama" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="font-bold text-xs sm:text-sm text-charcoal-950 truncate" x-text="item.nama"></h4>
                                        <p class="text-[11px] text-gold-700 font-semibold" x-text="formatRupiah(item.harga) + ' / porsi'"></p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 border-t sm:border-t-0 border-slate-200/60 pt-2 sm:pt-0">
                                    <!-- Stepper Qty -->
                                    <div class="flex items-center bg-white border border-slate-200 rounded-lg shadow-2xs">
                                        <button type="button" @click="kurangiQty(index)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-l-lg transition">
                                            <i class="fa-solid fa-minus text-[10px]"></i>
                                        </button>
                                        <span class="w-8 text-center text-xs font-bold text-charcoal-950 font-mono" x-text="item.qty"></span>
                                        <button type="button" @click="tambahQty(index)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-r-lg transition">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                        </button>
                                    </div>

                                    <!-- Subtotal Item -->
                                    <div class="w-24 text-right">
                                        <span class="font-mono font-bold text-xs sm:text-sm text-charcoal-950 tabular-nums" x-text="formatRupiah(item.harga * item.qty)"></span>
                                    </div>

                                    <!-- Hapus Item -->
                                    <button type="button" @click="hapusItem(index)" class="w-7 h-7 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 flex items-center justify-center transition" title="Hapus menu">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Card Pencarian Menu (Searchable Dropdown & Grid) -->
                <div class="bg-white border border-slate-200/90 rounded-2xl sm:rounded-3xl p-5 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="font-bold text-sm sm:text-base text-charcoal-950 flex items-center gap-2">
                                <i class="fa-solid fa-magnifying-glass text-gold-600"></i>
                                <span>Pilih Hidangan Laut Seafood</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Cari melalui kolom pencarian atau klik kategori di bawah.</p>
                        </div>
                        <span class="text-xs text-slate-500 font-mono" x-text="filteredMenus().length + ' Menu Tersedia'"></span>
                    </div>

                    <!-- Searchable Menu Dropdown Bar -->
                    <div class="relative" @click.outside="menuDropdownOpen = false">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="search"
                                   x-model="menuSearch"
                                   @focus="menuDropdownOpen = true"
                                   @input="menuDropdownOpen = true"
                                   placeholder="Ketik untuk mencari hidangan (Cth: Kepiting, Udang, Ikan Bakar)..."
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-9 pr-10 text-xs sm:text-sm text-charcoal-900 placeholder:text-slate-400 focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none transition">
                            <button type="button" x-show="menuSearch" @click="menuSearch = ''; menuDropdownOpen = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>

                        <!-- Autocomplete Dropdown List -->
                        <div x-show="menuDropdownOpen && menuSearch.trim().length > 0"
                             x-cloak x-transition
                             class="absolute left-0 right-0 z-40 mt-1.5 max-h-64 overflow-y-auto rounded-2xl border border-slate-200 bg-white p-1.5 shadow-2xl custom-scrollbar divide-y divide-slate-50">
                            <div x-show="dropdownMatchingMenus().length === 0" class="p-4 text-center text-xs text-slate-400">
                                Tidak ada menu yang cocok dengan "<span x-text="menuSearch" class="font-semibold text-charcoal-900"></span>".
                            </div>
                            <template x-for="m in dropdownMatchingMenus()" :key="m.id">
                                <div @click="addMenuToCart(m); menuDropdownOpen = false; menuSearch = ''"
                                     class="flex items-center justify-between p-2.5 rounded-xl hover:bg-gold-50/80 cursor-pointer transition gap-3 group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-200 shrink-0 bg-slate-100">
                                            <img :src="getMenuImage(m)" :alt="m.nama_menu" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-xs sm:text-sm text-charcoal-950 truncate group-hover:text-gold-900" x-text="m.nama_menu"></p>
                                            <p class="text-[11px] text-slate-400" x-text="m.kategori?.nama_kategori || 'Umum'"></p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-mono font-bold text-xs text-gold-600" x-text="formatRupiah(m.harga)"></p>
                                        <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                              :class="m.stok > 5 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                                              x-text="'Stok: ' + m.stok"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Category Filter Tabs -->
                    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar text-xs">
                        <button type="button" @click="activeKategori = 'semua'"
                                class="px-3 py-1.5 rounded-xl font-semibold whitespace-nowrap transition"
                                :class="activeKategori === 'semua' ? 'bg-charcoal-950 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'">
                            Semua Menu
                        </button>
                        <template x-for="k in kategoris" :key="k.id">
                            <button type="button" @click="activeKategori = String(k.id)"
                                    class="px-3 py-1.5 rounded-xl font-semibold whitespace-nowrap transition"
                                    :class="activeKategori === String(k.id) ? 'bg-charcoal-950 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    x-text="k.nama_kategori">
                            </button>
                        </template>
                    </div>

                    <!-- Visual Menu Grid Cards -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 pt-1 max-h-[72rem] sm:max-h-[48rem] xl:max-h-[36rem] overflow-y-auto pr-1 custom-scrollbar">
                        <template x-for="m in filteredMenus()" :key="m.id">
                            <div @click="addMenuToCart(m)"
                                 class="group relative h-44 bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-2xs hover:border-gold-400 hover:shadow-md cursor-pointer transition-all flex flex-col justify-between active:scale-[0.98]">
                                <div class="h-24 bg-slate-100 overflow-hidden relative">
                                    <img :src="getMenuImage(m)" :alt="m.nama_menu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute top-2 right-2 bg-charcoal-950/80 text-white text-[10px] font-bold px-2 py-0.5 rounded-full backdrop-blur-xs font-mono"
                                         :class="m.stok <= 5 ? 'bg-rose-900/90 text-rose-200' : ''"
                                         x-text="'Stok: ' + m.stok"></div>
                                </div>
                                <div class="p-2.5 flex flex-col justify-between flex-1">
                                    <div>
                                        <p class="text-[10px] font-medium text-slate-400 truncate" x-text="m.kategori?.nama_kategori || 'Seafood'"></p>
                                        <h4 class="font-bold text-xs sm:text-sm text-charcoal-950 leading-tight truncate mt-0.5" x-text="m.nama_menu"></h4>
                                    </div>
                                    <div class="mt-2.5 pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <span class="font-mono font-bold text-xs text-gold-600 tabular-nums" x-text="formatRupiah(m.harga)"></span>
                                        <span class="w-6 h-6 rounded-lg bg-gold-50 text-gold-700 group-hover:bg-gold-500 group-hover:text-charcoal-950 flex items-center justify-center transition text-xs font-bold shadow-2xs">
                                            <i class="fa-solid fa-plus text-[10px]"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="filteredMenus().length === 0" class="py-12 text-center text-slate-400">
                        <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300"></i>
                        <p class="text-xs sm:text-sm font-semibold text-charcoal-900">Tidak ada hidangan ditemukan.</p>
                        <p class="text-xs text-slate-400 mt-0.5">Coba ubah kata kunci pencarian atau ganti kategori.</p>
                    </div>

                </div>

            </div>

        </div>
    </form>
</div>

<script>
    (function() {
        function createKaryawanPosSystem(menus, pelangganOptions, kategoris) {
            return {
                menus: Array.isArray(menus) ? menus : [],
                pelangganOptions: Array.isArray(pelangganOptions) ? pelangganOptions : [],
                kategoris: Array.isArray(kategoris) ? kategoris : [],
                activeKategori: 'semua',
                menuSearch: '',
                menuDropdownOpen: false,

                selectedPelanggan: '',
                pelangganSearch: '',
                pelangganDropdownOpen: false,
                namaPelanggan: '',
                noHpPelanggan: '',

                tipePesanan: 'Dine-In',
                metodePembayaran: 'Tunai',
                statusPembayaran: 'Lunas',
                statusPesanan: 'Dimasak',
                cart: [],
                ongkir: 15000,

                init() {
                    if (typeof window.stopKaryawanAutoRefresh === 'function') {
                        window.stopKaryawanAutoRefresh();
                    }
                    this.$watch('tipePesanan', (val) => {
                        if (val === 'Delivery') {
                            if (this.metodePembayaran !== 'Tunai' && this.metodePembayaran !== 'COD') {
                                this.metodePembayaran = 'QRIS';
                            }
                        } else {
                            if (this.metodePembayaran === 'COD') {
                                this.metodePembayaran = 'Tunai';
                            }
                        }
                    });
                },

                filteredPelanggan() {
                    const query = (this.pelangganSearch || '').toLowerCase().trim();
                    if (!query) return this.pelangganOptions;
                    return this.pelangganOptions.filter((opt) =>
                        (opt.label && opt.label.toLowerCase().includes(query)) ||
                        (opt.no_hp && opt.no_hp.toLowerCase().includes(query)) ||
                        (opt.nama && opt.nama.toLowerCase().includes(query))
                    );
                },

                selectedPelangganLabel() {
                    const found = this.pelangganOptions.find((opt) => String(opt.id) === String(this.selectedPelanggan));
                    return found ? found.label : '-- Pelanggan Umum (Guest / Walk-In) --';
                },

                selectPelanggan(option) {
                    this.selectedPelanggan = String(option.id || '');
                    this.pelangganDropdownOpen = false;
                    this.pelangganSearch = '';

                    if (option.id) {
                        this.namaPelanggan = option.nama || '';
                        this.noHpPelanggan = option.no_hp || '';
                    } else {
                        this.namaPelanggan = '';
                        this.noHpPelanggan = '';
                    }
                },

                filteredMenus() {
                    const q = (this.menuSearch || '').toLowerCase().trim();
                    return this.menus.filter((m) => {
                        const matchKategori = (this.activeKategori === 'semua') || (String(m.kategori_id) === String(this.activeKategori));
                        const matchSearch = !q || (m.nama_menu && m.nama_menu.toLowerCase().includes(q));
                        return matchKategori && matchSearch;
                    });
                },

                dropdownMatchingMenus() {
                    const q = (this.menuSearch || '').toLowerCase().trim();
                    if (!q) return [];
                    return this.menus.filter((m) =>
                        (m.nama_menu && m.nama_menu.toLowerCase().includes(q)) ||
                        (m.kategori?.nama_kategori && m.kategori.nama_kategori.toLowerCase().includes(q))
                    ).slice(0, 8);
                },

                getMenuImage(menu) {
                    if (menu && menu.gambar) {
                        return '/storage/' + menu.gambar;
                    }
                    return '/image/seafood.webp';
                },

                addMenuToCart(menu) {
                    if (!menu) return;
                    const existingIndex = this.cart.findIndex(item => item.id === menu.id);
                    if (existingIndex !== -1) {
                        if (this.cart[existingIndex].qty < menu.stok) {
                            this.cart[existingIndex].qty++;
                        } else {
                            alert(`Stok ${menu.nama_menu} tidak mencukupi (tersisa ${menu.stok} porsi).`);
                        }
                    } else {
                        if (menu.stok > 0) {
                            this.cart.push({
                                id: menu.id,
                                nama: menu.nama_menu,
                                harga: Number(menu.harga),
                                qty: 1,
                                maxStok: Number(menu.stok),
                                image: this.getMenuImage(menu)
                            });
                        } else {
                            alert(`Stok ${menu.nama_menu} habis.`);
                        }
                    }
                },

                tambahQty(index) {
                    if (this.cart[index] && this.cart[index].qty < this.cart[index].maxStok) {
                        this.cart[index].qty++;
                    } else {
                        alert(`Stok maksimal tercapai (${this.cart[index]?.maxStok || 0} porsi).`);
                    }
                },

                kurangiQty(index) {
                    if (this.cart[index] && this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.hapusItem(index);
                    }
                },

                hapusItem(index) {
                    this.cart.splice(index, 1);
                },

                resetCart() {
                    if (confirm('Yakin ingin mengosongkan semua menu di keranjang kasir?')) {
                        this.cart = [];
                    }
                },

                subtotal() {
                    return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                },

                totalAkhir() {
                    let total = this.subtotal();
                    if (this.tipePesanan === 'Delivery') {
                        total += this.ongkir;
                    }
                    return total;
                },

                cartTotalPorsi() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },

                formatRupiah(num) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(num || 0);
                },

                handleSubmit(e) {
                    if (this.cart.length === 0) {
                        e.preventDefault();
                        alert('Keranjang kasir masih kosong. Pilih minimal 1 hidangan!');
                        return false;
                    }
                }
            };
        }

        window.karyawanPosSystem = createKaryawanPosSystem;
        if (window.Alpine) {
            window.Alpine.data('karyawanPosSystem', createKaryawanPosSystem);
        } else {
            document.addEventListener('alpine:init', () => {
                if (window.Alpine) {
                    window.Alpine.data('karyawanPosSystem', createKaryawanPosSystem);
                }
            });
        }
    })();
</script>
@endsection
