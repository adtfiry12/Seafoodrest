@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-8 border-b border-charcoal-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl sm:text-3xl font-serif text-white mb-2">Checkout Pesanan</h1>
        <p class="text-slate-400 text-sm font-light">Selesaikan pesanan Anda untuk segera kami proses.</p>
    </div>
</div>

<section class="bg-slate-50 py-12" x-data="checkoutPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div x-show="cart.length === 0" class="text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100" x-cloak>
            <i class="fa-solid fa-basket-shopping text-6xl text-slate-300 mb-6"></i>
            <h2 class="text-2xl font-serif text-charcoal-900 mb-2">Keranjang Anda Kosong</h2>
            <p class="text-slate-500 mb-8">Silakan pilih hidangan laut favorit Anda terlebih dahulu.</p>
            <a href="{{ route('menu') }}" class="px-8 py-3 bg-gold-600 text-white font-bold rounded-xl hover:bg-gold-500 transition uppercase tracking-wider text-sm shadow-lg">
                Kembali ke Menu
            </a>
        </div>

        <form action="{{ route('checkout.proses') }}" method="POST" @submit.prevent="handleSubmit($event)" x-show="cart.length > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12" x-cloak>
            @csrf

            <div class="lg:col-span-7 space-y-8">

                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-charcoal-900 mb-6 border-b border-slate-100 pb-3 flex items-center gap-3">
                        <i class="fa-solid fa-user text-gold-500"></i> Data Pemesan
                    </h3>

                    @guest
                    <div class="mb-5 p-4 bg-gold-50 border border-gold-200 rounded-xl text-sm text-gold-700 flex items-start gap-3">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <p>Anda checkout sebagai <strong>Tamu (Guest)</strong>. <a href="{{ route('login') }}" class="font-bold underline hover:text-gold-600">Login sekarang</a> agar pesanan tercatat di akun Anda.</p>
                    </div>
                    @endguest

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            @auth
                                <input type="text" name="nama" value="{{ Auth::user()->nama }}" readonly class="w-full bg-slate-100 border-none text-slate-500 rounded-xl px-4 py-3 text-sm focus:ring-0 cursor-not-allowed">
                            @else
                                <input type="text" name="nama" required placeholder="Nama Anda..." class="w-full bg-slate-50 border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">
                            @endauth
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ Auth::user()->no_hp ?? '' }}" required placeholder="Contoh: 08123456789" class="w-full bg-slate-50 border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-charcoal-900 mb-6 border-b border-slate-100 pb-3 flex items-center gap-3">
                        <i class="fa-solid fa-bell-concierge text-gold-500"></i> Detail Pengiriman / Layanan
                    </h3>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Tipe Pesanan</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                                   :class="tipePesanan === 'Makan di Tempat' ? 'border-gold-500 bg-gold-50 text-gold-700' : 'border-slate-200 bg-white text-slate-500 hover:bg-slate-50'">
                                <input type="radio" name="tipe_pesanan" value="Makan di Tempat" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-store text-2xl mb-2"></i>
                                <span class="text-sm font-bold">Makan di Tempat</span>
                            </label>

                            <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                                   :class="tipePesanan === 'Delivery' ? 'border-gold-500 bg-gold-50 text-gold-700' : 'border-slate-200 bg-white text-slate-500 hover:bg-slate-50'">
                                <input type="radio" name="tipe_pesanan" value="Delivery" x-model="tipePesanan" class="sr-only">
                                <i class="fa-solid fa-motorcycle text-2xl mb-2"></i>
                                <span class="text-sm font-bold">Delivery</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="tipePesanan === 'Makan di Tempat'" x-collapse class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Status Kedatangan Anda</label>

                        <div class="flex gap-4 mb-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="opsi_dinein" value="sekarang" x-model="opsiDineIn" class="text-gold-500 focus:ring-gold-500">
                                <span class="ml-2 text-sm text-charcoal-900 font-medium">Sudah di Restoran</span>
                            </label>

                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="opsi_dinein" value="reservasi" x-model="opsiDineIn" class="text-gold-500 focus:ring-gold-500">
                                <span class="ml-2 text-sm text-charcoal-900 font-medium">Reservasi (Pesan dari luar)</span>
                            </label>
                        </div>

                        <div x-show="opsiDineIn === 'sekarang'" x-collapse>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Meja</label>
                            <input type="text" name="nomor_meja" placeholder="Contoh: Meja 05" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">
                        </div>

                        <div x-show="opsiDineIn === 'reservasi'" x-collapse class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Rencana Jam Kedatangan</label>
                                <input type="time" name="jam_kedatangan" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Jumlah Orang (Pax)</label>
                                <input type="number" name="jumlah_orang" placeholder="Contoh: 4" min="1" class="w-full bg-white border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">
                            </div>
                        </div>
                    </div>

                    <div x-show="tipePesanan === 'Delivery'" x-collapse>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Pengiriman Lengkap</label>
                        <textarea name="alamat" rows="3" placeholder="Tuliskan jalan, nomor rumah, RT/RW, dan patokan..." class="w-full bg-slate-50 border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500">{{ Auth::user()->alamat ?? '' }}</textarea>
                    </div>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-charcoal-900 mb-6 border-b border-slate-100 pb-3 flex items-center gap-3">
                        <i class="fa-solid fa-wallet text-gold-500"></i> Metode Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <label x-show="tipePesanan === 'Makan di Tempat'" class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'Kasir' ? 'border-gold-500 bg-gold-50' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <input type="radio" name="metode_pembayaran" value="Kasir" x-model="metodePembayaran" class="text-gold-500 focus:ring-gold-500">
                            <div class="ml-4">
                                <span class="block text-sm font-bold text-charcoal-900">Bayar di Kasir</span>
                                <span class="block text-xs text-slate-500">Bayar langsung ke kasir setelah selesai makan.</span>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'QRIS' ? 'border-gold-500 bg-gold-50' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <input type="radio" name="metode_pembayaran" value="QRIS" x-model="metodePembayaran" class="text-gold-500 focus:ring-gold-500">
                            <div class="ml-4">
                                <span class="block text-sm font-bold text-charcoal-900">QRIS (Gopay, OVO, Dana, M-Banking)</span>
                                <span class="block text-xs text-slate-500">Scan QR Code instan tanpa biaya admin.</span>
                            </div>
                        </label>

                        <label x-show="tipePesanan === 'Delivery'" class="flex items-center p-4 border rounded-xl cursor-pointer transition-all"
                               :class="metodePembayaran === 'COD' ? 'border-gold-500 bg-gold-50' : 'border-slate-200 bg-white hover:bg-slate-50'">
                            <input type="radio" name="metode_pembayaran" value="COD" x-model="metodePembayaran" class="text-gold-500 focus:ring-gold-500">
                            <div class="ml-4">
                                <span class="block text-sm font-bold text-charcoal-900">Bayar di Tempat (COD)</span>
                                <span class="block text-xs text-slate-500">Bayar tunai kepada kurir kami saat makanan tiba.</span>
                            </div>
                        </label>
                    </div>

                    <div class="mt-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Cth: Sambal dipisah, minta sendok ekstra..." class="w-full bg-slate-50 border border-slate-200 text-charcoal-900 rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500"></textarea>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-5">
                <div class="bg-charcoal-950 rounded-3xl shadow-2xl p-6 sm:p-8 sticky top-28">
                    <h3 class="text-lg font-serif font-bold text-white mb-6 border-b border-charcoal-800 pb-3">Ringkasan Pesanan</h3>

                    <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex gap-4 items-start">
                                <input type="hidden" :name="`items[${index}][menu_id]`" :value="item.id">
                                <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">
                                <input type="hidden" :name="`items[${index}][harga]`" :value="item.harga">

                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 border border-charcoal-800">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 text-sm">
                                    <p class="font-bold text-white leading-tight" x-text="item.nama"></p>
                                    <p class="text-slate-400 mt-1" x-text="item.qty + ' Porsi x Rp ' + formatRibuan(item.harga)"></p>
                                </div>
                                <div class="font-bold text-gold-500 text-sm whitespace-nowrap">
                                    <p x-text="'Rp ' + formatRibuan(item.harga * item.qty)"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-charcoal-800 pt-6 space-y-3 text-sm">
                        <div class="flex justify-between text-slate-300">
                            <span>Subtotal</span>
                            <span class="font-bold" x-text="'Rp ' + formatRibuan(subtotal())"></span>
                        </div>
                        <div x-show="tipePesanan === 'Delivery'" class="flex justify-between text-slate-300" x-cloak>
                            <span>Ongkos Kirim (Flat)</span>
                            <span class="font-bold" x-text="'Rp ' + formatRibuan(ongkir)"></span>
                        </div>
                        <div class="flex justify-between text-white text-lg font-bold pt-3 border-t border-charcoal-800">
                            <span>Total Bayar</span>
                            <span class="text-gold-500" x-text="'Rp ' + formatRibuan(totalAkhir())"></span>
                        </div>
                        <input type="hidden" name="total_bayar" :value="totalAkhir()">
                    </div>

                    <button type="submit" class="w-full mt-8 px-8 py-4 bg-gold-600 text-white font-bold rounded-xl hover:bg-gold-500 transition uppercase tracking-wider text-sm shadow-lg shadow-gold-600/20 flex justify-center items-center gap-3">
                        Buat Pesanan <i class="fa-solid fa-check-circle text-lg"></i>
                    </button>
                    <p class="text-center text-slate-500 text-[11px] mt-4">Pesanan yang sudah dibuat tidak dapat dibatalkan.</p>
                </div>
            </div>

        </form>
    </div>

    <div x-show="loginModalOpen" x-cloak class="fixed inset-0 z-50 flex min-h-screen items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="login-modal-title">
        <div x-show="loginModalOpen" x-transition.opacity @click="loginModalOpen = false" class="absolute inset-0 bg-charcoal-950/70 backdrop-blur-sm"></div>

        <div x-show="loginModalOpen" x-transition class="relative my-auto w-full max-w-sm overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-2xl">
            <div class="h-1.5 bg-gold-500"></div>
            <div class="px-6 pb-6 pt-8 text-center sm:px-8">
                <div class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl border border-gold-200 bg-gold-50 text-gold-600 shadow-sm">
                    <i class="fa-solid fa-lock text-2xl"></i>
                </div>
                <h2 id="login-modal-title" class="font-serif text-2xl font-bold text-charcoal-900">Login untuk COD</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-500">Pembayaran COD untuk pesanan delivery memerlukan akun yang sudah login.</p>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:flex-row sm:px-8">
                <button type="button" @click="loginModalOpen = false" class="flex-1 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50">Kembali</button>
                <a href="{{ route('login') }}" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-charcoal-900 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-gold-600">
                    Login Sekarang <i class="fa-solid fa-arrow-right text-xs"></i>
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
