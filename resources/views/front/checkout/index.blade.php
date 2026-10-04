@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-8 sm:py-10 border-b border-charcoal-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-semibold uppercase tracking-wider text-gold-400">Pemesanan Kuliner</span>
        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white mt-1">Checkout Pesanan</h1>
        <p class="text-slate-400 text-sm mt-1 max-w-xl font-normal leading-relaxed">Selesaikan rincian pesanan Anda untuk segera disiapkan oleh dapur kami.</p>
    </div>
</div>

<section class="bg-slate-50/70 py-10 sm:py-14" x-data="checkoutPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div x-show="cart.length === 0" class="text-center py-20 bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/80" x-cloak>
            <div class="w-16 h-16 rounded-2xl bg-gold-50 text-gold-600 flex items-center justify-center text-3xl mx-auto mb-4 border border-gold-100">
                <i class="fa-solid fa-basket-shopping"></i>
            </div>
            <h2 class="text-2xl font-serif font-bold text-charcoal-950 mb-2">Keranjang Anda Kosong</h2>
            <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">Silakan pilih hidangan laut favorit Anda terlebih dahulu.</p>
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-charcoal-900 text-white font-semibold rounded-xl hover:bg-gold-600 transition text-sm shadow-xs">
                <span>Kembali ke Menu</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        @if($errors->any() || session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200/90 bg-rose-50/90 p-4 sm:p-5 text-rose-900 shadow-xs" role="alert">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 text-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1 text-xs sm:text-sm">
                        <h4 class="font-bold text-rose-950 mb-1">Perhatian Sebelum Memproses Pesanan:</h4>
                        @if(session('error'))
                            <p class="leading-relaxed">{{ session('error') }}</p>
                        @endif
                        @if($errors->any())
                            <ul class="list-disc list-inside space-y-0.5 leading-relaxed">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.proses') }}" method="POST" @submit.prevent="handleSubmit($event)" x-show="cart.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10" x-cloak>
            @csrf

            <div class="lg:col-span-7 space-y-6">

                <!-- Data Pemesan -->
                <div class="bg-white p-6 sm:p-7 rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/80">
                    <h3 class="text-base sm:text-lg font-serif font-bold text-charcoal-950 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-gold-50 text-gold-700 border border-gold-100 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <span>Data Pemesan</span>
                    </h3>

                    @guest
                    <div class="mb-5 p-3.5 bg-amber-50/70 border border-amber-200/70 rounded-xl text-xs sm:text-sm text-amber-900 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0"></i>
                        <p>Anda checkout sebagai <strong>Tamu (Guest)</strong>. <a href="{{ route('login') }}" class="font-semibold text-amber-950 underline hover:text-gold-700">Login sekarang</a> agar pesanan tercatat di akun Anda.</p>
                    </div>
                    @endguest

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                            @auth
                                <input type="text" name="nama" value="{{ Auth::user()->nama }}" readonly class="w-full bg-slate-100/80 border border-slate-200 text-slate-600 rounded-xl px-4 py-2.5 text-sm cursor-not-allowed">
                            @else
                                <input type="text" name="nama" required placeholder="Nama Anda..." class="w-full bg-slate-50/70 border border-slate-200/80 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">
                            @endauth
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">No. WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ Auth::user()->no_hp ?? '' }}" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50/70 border border-slate-200/80 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Detail Pengiriman / Layanan -->
                <div class="bg-white p-6 sm:p-7 rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/80">
                    <h3 class="text-base sm:text-lg font-serif font-bold text-charcoal-950 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-gold-50 text-gold-700 border border-gold-100 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bell-concierge"></i>
                        </span>
                        <span>Detail Pengiriman / Layanan</span>
                    </h3>

                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Tipe Pesanan</label>
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <label class="relative flex flex-col items-center justify-center p-3.5 sm:p-4 border rounded-xl cursor-pointer transition-all"
                                   :class="tipePesanan === 'Makan di Tempat' ? 'border-gold-500 bg-gold-50/50 text-gold-900 ring-1 ring-gold-500/25' : 'border-slate-200/80 bg-white text-slate-600 hover:bg-slate-50/60'">
                                <input type="radio" name="tipe_pesanan" value="Makan di Tempat" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-store text-xl mb-1.5"></i>
                                <span class="text-xs sm:text-sm font-semibold">Makan di Tempat</span>
                            </label>

                            <label class="relative flex flex-col items-center justify-center p-3.5 sm:p-4 border rounded-xl cursor-pointer transition-all"
                                   :class="tipePesanan === 'Delivery' ? 'border-gold-500 bg-gold-50/50 text-gold-900 ring-1 ring-gold-500/25' : 'border-slate-200/80 bg-white text-slate-600 hover:bg-slate-50/60'">
                                <input type="radio" name="tipe_pesanan" value="Delivery" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-motorcycle text-xl mb-1.5"></i>
                                <span class="text-xs sm:text-sm font-semibold">Delivery</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="tipePesanan === 'Makan di Tempat'" x-collapse class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/80 mb-5">
                        <label class="block text-xs font-semibold text-slate-600 mb-2.5">Status Kedatangan Anda</label>

                        <div class="flex flex-wrap gap-4 mb-3.5">
                            <label class="flex items-center cursor-pointer text-xs sm:text-sm text-charcoal-900">
                                <input type="radio" name="opsi_dinein" value="sekarang" x-model="opsiDineIn" class="text-gold-600 focus:ring-gold-500">
                                <span class="ml-2 font-medium">Sudah di Restoran</span>
                            </label>

                            <label class="flex items-center cursor-pointer text-xs sm:text-sm text-charcoal-900">
                                <input type="radio" name="opsi_dinein" value="reservasi" x-model="opsiDineIn" class="text-gold-600 focus:ring-gold-500">
                                <span class="ml-2 font-medium">Reservasi (Pesan dari luar)</span>
                            </label>
                        </div>

                        <div x-show="opsiDineIn === 'sekarang'" x-collapse>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor Meja</label>
                            <input type="text" name="nomor_meja" placeholder="Contoh: Meja 05" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">
                        </div>

                        <div x-show="opsiDineIn === 'reservasi'" x-collapse class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Rencana Jam Kedatangan</label>
                                <input type="time" name="jam_kedatangan" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah Orang (Pax)</label>
                                <input type="number" name="jumlah_orang" placeholder="Contoh: 4" min="1" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">
                            </div>
                        </div>
                    </div>

                    <div x-show="tipePesanan === 'Delivery'" x-collapse class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alamat Pengiriman Lengkap</label>
                            <textarea name="alamat" rows="3" placeholder="Tuliskan jalan, nomor rumah, RT/RW, kelurahan/kecamatan..." class="w-full bg-slate-50/70 border border-slate-200/80 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">{{ Auth::user()->alamat ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Patokan / Catatan Alamat (Opsional)</label>
                            <input type="text" name="patokan_alamat" placeholder="Contoh: Pagar hitam depan masjid, samping warung kelontong" class="w-full bg-slate-50/70 border border-slate-200/80 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400">
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white p-6 sm:p-7 rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/80">
                    <h3 class="text-base sm:text-lg font-serif font-bold text-charcoal-950 mb-5 border-b border-slate-100 pb-3 flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-lg bg-gold-50 text-gold-700 border border-gold-100 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-wallet"></i>
                        </span>
                        <span>Metode Pembayaran</span>
                    </h3>

                    <div class="space-y-2.5">
                        <label x-show="tipePesanan === 'Makan di Tempat'" class="flex items-center p-3.5 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'Kasir' ? 'border-gold-500 bg-gold-50/40 ring-1 ring-gold-500/25' : 'border-slate-200/80 bg-white hover:bg-slate-50/60'">
                            <input type="radio" name="metode_pembayaran" value="Kasir" x-model="metodePembayaran" class="text-gold-600 focus:ring-gold-500">
                            <div class="ml-3.5">
                                <span class="block text-sm font-semibold text-charcoal-950">Bayar di Kasir</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Bayar langsung ke kasir setelah selesai makan.</span>
                            </div>
                        </label>

                        <label class="flex items-center p-3.5 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'QRIS' ? 'border-gold-500 bg-gold-50/40 ring-1 ring-gold-500/25' : 'border-slate-200/80 bg-white hover:bg-slate-50/60'">
                            <input type="radio" name="metode_pembayaran" value="QRIS" x-model="metodePembayaran" class="text-gold-600 focus:ring-gold-500">
                            <div class="ml-3.5">
                                <span class="block text-sm font-semibold text-charcoal-950">QRIS / Transfer Bank</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Scan QRIS (E-Wallet & M-Banking) atau Transfer langsung ke BCA & BSI.</span>
                            </div>
                        </label>

                        <label x-show="tipePesanan === 'Delivery'" class="flex items-center p-3.5 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'COD' ? 'border-gold-500 bg-gold-50/40 ring-1 ring-gold-500/25' : 'border-slate-200/80 bg-white hover:bg-slate-50/60'">
                            <input type="radio" name="metode_pembayaran" value="COD" x-model="metodePembayaran" class="text-gold-600 focus:ring-gold-500">
                            <div class="ml-3.5">
                                <span class="block text-sm font-semibold text-charcoal-950">Bayar di Tempat (COD)</span>
                                <span class="block text-xs text-slate-500 mt-0.5">Bayar tunai kepada kurir kami saat makanan tiba.</span>
                            </div>
                        </label>
                    </div>

                    <div class="mt-5">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Cth: Sambal dipisah, minta sendok ekstra..." class="w-full bg-slate-50/70 border border-slate-200/80 text-charcoal-900 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition placeholder:text-slate-400"></textarea>
                    </div>
                </div>

            </div>

            <!-- Ringkasan Pesanan (Sticky Right Column) -->
            <div class="lg:col-span-5">
                <div class="bg-charcoal-950 border border-charcoal-800/80 rounded-2xl sm:rounded-3xl shadow-sm p-6 sm:p-7 lg:sticky lg:top-24 text-white">
                    <h3 class="text-base sm:text-lg font-serif font-bold text-white mb-5 border-b border-charcoal-800 pb-3 flex items-center justify-between">
                        <span>Ringkasan Pesanan</span>
                        <span class="text-xs font-mono text-gold-400" x-text="cart.length + ' Item'"></span>
                    </h3>

                    <div class="space-y-3.5 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex gap-3.5 items-start">
                                <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.id">
                                <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">

                                <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 border border-charcoal-800 bg-charcoal-900">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0 text-sm">
                                    <p class="font-semibold text-white leading-tight truncate" x-text="item.nama"></p>
                                    <p class="text-xs text-slate-400 mt-1" x-text="item.qty + ' Porsi × Rp ' + formatRibuan(item.harga)"></p>
                                </div>
                                <div class="font-semibold text-gold-400 text-sm whitespace-nowrap tabular-nums">
                                    <p x-text="'Rp ' + formatRibuan(item.harga * item.qty)"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-charcoal-800/80 pt-5 space-y-2.5 text-sm">
                        <div class="flex justify-between text-slate-300">
                            <span>Subtotal</span>
                            <span class="font-semibold text-white tabular-nums" x-text="'Rp ' + formatRibuan(subtotal())"></span>
                        </div>
                        <div x-show="tipePesanan === 'Delivery'" class="flex justify-between text-slate-300" x-cloak>
                            <span>Ongkos Kirim (Flat)</span>
                            <span class="font-semibold text-white tabular-nums" x-text="'Rp ' + formatRibuan(ongkir)"></span>
                        </div>
                        <div class="flex justify-between items-baseline text-white text-base sm:text-lg font-bold pt-3 border-t border-charcoal-800">
                            <span class="font-serif">Total Bayar</span>
                            <span class="text-gold-400 font-serif tabular-nums text-xl" x-text="'Rp ' + formatRibuan(totalAkhir())"></span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 px-6 py-3.5 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white font-semibold rounded-xl transition text-sm shadow-xs flex justify-center items-center gap-2">
                        <span>Buat Pesanan</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                    <p class="text-center text-slate-500 text-[11px] mt-3">Pesanan yang sudah dibuat akan segera diproses oleh staf dapur.</p>
                </div>
            </div>

        </form>
    </div>

    <!-- Login Modal untuk COD -->
    <div x-show="loginModalOpen" x-cloak class="fixed inset-0 z-50 flex min-h-screen items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="login-modal-title">
        <div x-show="loginModalOpen" x-transition.opacity @click="loginModalOpen = false" class="absolute inset-0 bg-charcoal-950/70 backdrop-blur-sm"></div>

        <div x-show="loginModalOpen" x-transition class="relative my-auto w-full max-w-sm overflow-hidden rounded-2xl sm:rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="px-6 pb-6 pt-7 text-center sm:px-8">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-gold-200 bg-gold-50 text-gold-600 shadow-xs">
                    <i class="fa-solid fa-lock text-xl"></i>
                </div>
                <h2 id="login-modal-title" class="font-serif text-xl sm:text-2xl font-bold text-charcoal-950">Login untuk COD</h2>
                <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-500">Pembayaran COD untuk pesanan delivery memerlukan akun yang sudah terdaftar dan masuk.</p>
            </div>

            <div class="flex flex-col-reverse gap-2.5 border-t border-slate-100 bg-slate-50/80 px-6 py-4 sm:flex-row sm:px-8">
                <button type="button" @click="loginModalOpen = false" class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Kembali</button>
                <a href="{{ route('login') }}" class="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-charcoal-900 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs transition hover:bg-gold-600">
                    <span>Login</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #18181b; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutPage', () => ({
            cart: [],
            isGuest: @json(auth()->guest()),
            loginModalOpen: false,
            tipePesanan: 'Makan di Tempat',
            opsiDineIn: 'sekarang',
            metodePembayaran: 'Kasir',
            ongkir: 15000,

            init() {
                this.cart = JSON.parse(localStorage.getItem('niskala_cart') || '[]');

                this.$watch('tipePesanan', (value) => {
                    if(value === 'Delivery') {
                        this.metodePembayaran = 'QRIS';
                    } else {
                        this.metodePembayaran = 'Kasir';
                    }
                });
            },

            handleSubmit(event) {
                if (this.isGuest && this.tipePesanan === 'Delivery' && this.metodePembayaran === 'COD') {
                    this.loginModalOpen = true;
                    return;
                }

                event.target.submit();
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

            formatRibuan(angka) {
                return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(angka);
            }
        }));
    });
</script>

@endsection
