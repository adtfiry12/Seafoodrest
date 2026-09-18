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

                    <div>
                        <label class="block text-sm font-semibold text-charcoal-900 mb-2">Pelanggan</label>
                        <select name="pengguna_id" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                            <option value="">-- Pelanggan Umum (Guest) --</option>
                            @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }} - {{ $p->no_hp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Pemesan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" placeholder="Nama pelanggan umum" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="no_hp_pelanggan" class="block text-sm font-semibold text-charcoal-900 mb-2">No. HP Pemesan</label>
                        <input type="text" name="no_hp_pelanggan" id="no_hp_pelanggan" placeholder="Nomor WhatsApp pelanggan" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-gold-500 block w-full p-2.5">
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
                            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-100 rounded-xl">
                                <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.id">
                                <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">

                                <div class="flex-1">
                                    <p class="font-bold text-charcoal-900 text-sm" x-text="item.nama"></p>
                                    <p class="text-xs text-gold-600 font-medium" x-text="formatRupiah(item.harga)"></p>
                                </div>

                                <div class="flex items-center gap-3">
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
                    <h3 class="font-bold text-charcoal-900 mb-3">Pilih Menu Seafood</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($menu as $m)
                            <div @click="addToCart({{ $m->id }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }}, {{ $m->stok }})"
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
    document.addEventListener('alpine:init', () => {
        Alpine.data('posSystem', (menus) => ({
            tipePesanan: 'Dine-In',
            cart: [],

            addToCart(id, nama, harga, maxStok) {
                let index = this.cart.findIndex(item => item.id === id);
                if (index !== -1) {
                    if(this.cart[index].qty < maxStok) {
                        this.cart[index].qty++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                } else {
                    this.cart.push({ id, nama, harga, qty: 1, maxStok });
                }
            },

            tambahQty(index) {
                if(this.cart[index].qty < this.cart[index].maxStok) {
                    this.cart[index].qty++;
                }
            },

            kurangiQty(index) {
                if (this.cart[index].qty > 1) {
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
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            }
        }));
    });
</script>
@endsection
