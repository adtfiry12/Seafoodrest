@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
     x-data="posSystem({{ $menu->toJson() }})">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Kasir / Transaksi Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Pilih menu dan proses pesanan secara langsung.</p>
        </div>
        <a href="{{ route('admin.transaksi.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-charcoal-900 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.transaksi.store') }}" method="POST" id="form-transaksi">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                    <h3 class="font-bold text-charcoal-900 border-b border-slate-100 pb-2">Detail Pesanan</h3>

                    @php
                        $pelangganOptions = collect([
                            ['id' => '', 'nama' => '', 'no_hp' => '', 'label' => '-- Pelanggan Umum (Guest) --']
                        ])->merge(
                            $pelanggan->map(fn($p) => [
                                'id' => (string) $p->id,
                                'nama' => $p->nama,
                                'no_hp' => $p->no_hp ?? '',
                                'label' => $p->nama . ($p->no_hp ? ' (' . $p->no_hp . ')' : '')
                            ])
                        )->values();
                    @endphp

                    <div>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Pelanggan</label>
                        <div x-data="searchablePelangganSelect(@js($pelangganOptions), @js((string) old('pengguna_id')))" @click.outside="open = false" class="relative">
                            <input type="hidden" name="pengguna_id" :value="selected">
                            <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20">
                                <span class="truncate" x-text="selectedLabel() || '-- Pelanggan Umum (Guest) --'" :class="selected ? 'text-charcoal-900 font-medium' : 'text-slate-500'"></span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>
                            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                                <div class="border-b border-slate-100 p-2 bg-slate-50/50">
                                    <div class="relative flex items-center">
                                        <i class="fa-solid fa-magnifying-glass absolute left-3 text-xs text-slate-400 pointer-events-none"></i>
                                        <input type="text"
                                               x-model="search"
                                               placeholder="Cari nama atau no. HP..."
                                               class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-8 pr-8 text-xs sm:text-sm text-charcoal-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none">
                                        <button type="button"
                                                x-show="search && search.length > 0"
                                                @click="search = ''"
                                                class="absolute right-2.5 text-slate-400 hover:text-slate-600 p-0.5"
                                                style="display: none;">
                                            <i class="fa-solid fa-xmark text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="max-h-56 overflow-y-auto p-1 custom-scrollbar divide-y divide-slate-50">
                                    <button type="button" x-show="filteredOptions().length === 0" class="w-full px-3 py-3 text-center text-xs text-slate-400" disabled>Tidak ada data pelanggan ditemukan.</button>
                                    <template x-for="option in filteredOptions()" :key="option.id">
                                        <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-xs sm:text-sm text-charcoal-900 hover:bg-gold-50 hover:text-gold-700 transition">
                                            <div class="truncate">
                                                <span class="font-medium" x-text="option.nama || option.label"></span>
                                                <span x-show="option.no_hp" class="text-xs text-slate-400 ml-1.5" x-text="'(' + option.no_hp + ')'"></span>
                                            </div>
                                            <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600 shrink-0 ml-2"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        @error('pengguna_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Pemesan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="{{ old('nama_pelanggan') }}" placeholder="Nama pelanggan umum" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                        @error('nama_pelanggan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="no_hp_pelanggan" class="block text-sm font-semibold text-charcoal-900 mb-2">No. HP Pemesan</label>
                        <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" value="{{ old('no_hp_pelanggan') }}" placeholder="Nomor WhatsApp pelanggan" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                        @error('no_hp_pelanggan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Tipe Pesanan <span class="text-red-500">*</span></label>
                        <select name="tipe_pesanan" required x-model="tipePesanan" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                            <option value="Dine-In">Dine-In (Makan di Tempat)</option>
                            <option value="Takeaway">Takeaway (Bungkus)</option>
                            <option value="Delivery">Delivery (Kirim)</option>
                        </select>
                    </div>

                    <div x-show="tipePesanan === 'Delivery'" x-cloak>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Alamat Pengiriman</label>
                        <textarea name="alamat" rows="2" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_pembayaran" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="QRIS">QRIS / Transfer</option>
                            <option value="COD">Bayar di Tempat (COD)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" placeholder="Cth: Pedas, tanpa saus..." class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5"></textarea>
                    </div>
                </div>

                <div class="bg-charcoal-900 rounded-2xl p-5 shadow-lg text-white">
                    <h3 class="font-bold border-b border-charcoal-700 pb-2 mb-4">Total Pembayaran</h3>
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-slate-400">Total Harga</span>
                        <span class="text-2xl font-bold text-gold-400" x-text="formatRupiah(totalBelanja())">Rp 0</span>
                    </div>

                    <button type="submit"
                            :disabled="cart.length === 0"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-charcoal-900 bg-gold-500 rounded-xl hover:bg-gold-400 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                        <i class="fa-solid fa-print"></i> Proses Transaksi
                    </button>
                    @error('items') <p class="text-xs text-red-400 mt-2 text-center">Pilih minimal 1 menu!</p> @enderror
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                    <h3 class="font-bold text-charcoal-900 border-b border-slate-100 pb-2 mb-4">Keranjang Pesanan</h3>

                    <div x-show="cart.length === 0" class="text-center py-6 text-slate-400 text-sm">
                        Keranjang masih kosong. Klik menu di bawah untuk menambahkan.
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl gap-2.5 sm:gap-3">
                                <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.id">
                                <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">

                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-charcoal-900 text-sm truncate" x-text="item.nama"></p>
                                    <p class="text-xs text-gold-600 font-medium" x-text="formatRupiah(item.harga) + ' / porsi'"></p>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-3 border-t sm:border-t-0 border-slate-200/60 pt-2 sm:pt-0">
                                    <div class="flex items-center bg-white border border-slate-200 rounded-lg">
                                        <button type="button" @click="kurangiQty(index)" class="px-2.5 py-1 text-slate-500 hover:text-red-500"><i class="fa-solid fa-minus text-xs"></i></button>
                                        <span class="px-2 text-sm font-bold text-charcoal-900" x-text="item.qty"></span>
                                        <button type="button" @click="tambahQty(index)" class="px-2.5 py-1 text-slate-500 hover:text-emerald-500"><i class="fa-solid fa-plus text-xs"></i></button>
                                    </div>
                                    <p class="font-bold text-charcoal-900 text-sm w-20 text-right" x-text="formatRupiah(item.harga * item.qty)"></p>
                                    <button type="button" @click="hapusItem(index)" class="text-red-400 hover:text-red-600 p-1"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-3">
                        <h3 class="font-bold text-charcoal-900">Pilih Menu Seafood</h3>
                        <div class="relative w-full sm:w-64 flex items-center">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 text-xs text-slate-400 pointer-events-none"></i>
                            <input type="text"
                                   x-model="menuFilterSearch"
                                   placeholder="Cari menu hidangan..."
                                   class="w-full rounded-xl border border-slate-200 bg-white py-1.5 pl-8 pr-8 text-xs text-charcoal-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none">
                            <button type="button"
                                    x-show="menuFilterSearch && menuFilterSearch.length > 0"
                                    @click="menuFilterSearch = ''"
                                    class="absolute right-2.5 text-slate-400 hover:text-slate-600 p-0.5"
                                    style="display: none;">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($menu as $m)
                            <div x-show="!menuFilterSearch || '{{ strtolower(addslashes($m->nama_menu)) }}'.includes(menuFilterSearch.toLowerCase().trim())"
                                 @click="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }})"
                                 class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:border-gold-500 hover:shadow-md cursor-pointer transition-all group">
                                <div class="h-24 bg-slate-100 overflow-hidden relative">
                                    @if($m->gambar)
                                        <img src="{{ asset('storage/' . $m->gambar) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    @else
                                        <div class="flex items-center justify-center w-full h-full text-slate-300"><i class="fa-solid fa-utensils text-2xl"></i></div>
                                    @endif
                                    <div class="absolute top-2 right-2 bg-charcoal-900/80 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold backdrop-blur-sm">
                                        Stok: {{ $m->stok }}
                                    </div>
                                </div>
                                <div class="p-3">
                                    <p class="text-sm font-bold text-charcoal-900 leading-tight mb-1 truncate">{{ $m->nama_menu }}</p>
                                    <p class="text-xs font-bold text-gold-600">Rp {{ number_format($m->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    (function() {
        function registerCreateTransaksiComponents() {
            if (!window.Alpine) return;

            window.searchablePelangganSelect = function(options, initialValue = '') {
                return {
                    open: false,
                    search: '',
                    selected: String(initialValue || ''),
                    options: options || [],

                    filteredOptions() {
                        const query = (this.search || '').toLowerCase().trim();
                        if (!query) return this.options;
                        return this.options.filter((opt) =>
                            (opt.label && opt.label.toLowerCase().includes(query)) ||
                            (opt.no_hp && opt.no_hp.toLowerCase().includes(query)) ||
                            (opt.nama && opt.nama.toLowerCase().includes(query))
                        );
                    },

                    selectedLabel() {
                        const found = this.options.find((opt) => String(opt.id) === this.selected);
                        return found ? found.label : '-- Pelanggan Umum (Guest) --';
                    },

                    select(option) {
                        this.selected = String(option.id || '');
                        this.open = false;
                        this.search = '';

                        const namaInput = document.getElementById('nama_pelanggan');
                        const hpInput = document.getElementById('no_hp_pelanggan');
                        if (option.id) {
                            if (namaInput) namaInput.value = option.nama || '';
                            if (hpInput) hpInput.value = option.no_hp || '';
                        } else {
                            if (namaInput) namaInput.value = '';
                            if (hpInput) hpInput.value = '';
                        }
                    }
                };
            };

            window.posSystem = function(menus) {
                return {
                    tipePesanan: 'Dine-In',
                    cart: [],
                    menuFilterSearch: '',

                    addToCart(id, nama, harga, maxStok) {
                        let index = this.cart.findIndex(item => item.id === id);
                        if (index !== -1) {
                            if (this.cart[index] && this.cart[index].qty < maxStok) {
                                this.cart[index].qty++;
                            } else {
                                alert('Stok tidak mencukupi!');
                            }
                        } else {
                            this.cart.push({ id, nama, harga, qty: 1, maxStok });
                        }
                    },

                    tambahQty(index) {
                        if (this.cart[index] && this.cart[index].qty < this.cart[index].maxStok) {
                            this.cart[index].qty++;
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

                    totalBelanja() {
                        return this.cart.reduce((total, item) => total + (item.harga * item.qty), 0);
                    },

                    formatRupiah(angka) {
                        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka || 0);
                    }
                };
            };

            window.Alpine.data('searchablePelangganSelect', window.searchablePelangganSelect);
            window.Alpine.data('posSystem', window.posSystem);
        }

        if (window.Alpine) {
            registerCreateTransaksiComponents();
        } else {
            document.addEventListener('alpine:init', registerCreateTransaksiComponents);
        }
    })();
</script>
@endsection
