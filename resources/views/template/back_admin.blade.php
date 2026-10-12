<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Niskala Samudra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased"
      @close-sidebar.window="sidebarOpen = false"
      x-data="{ sidebarOpen: false, deleteModalOpen: false, deleteForm: null, deleteMessage: '', actionModalOpen: false, actionForm: null, actionTitle: '', actionMessage: '', openDeleteModal(event, message) { this.deleteForm = event.currentTarget; this.deleteMessage = message; this.deleteModalOpen = true; }, openActionModal(event, title, message) { this.actionForm = event.currentTarget; this.actionTitle = title; this.actionMessage = message; this.actionModalOpen = true; } }">
    {{-- Global Progress Bar for Admin SPA Navigation --}}
    <div id="pjax-progress" class="fixed top-0 left-0 right-0 h-[2px] z-[100] transition-all duration-300 pointer-events-none opacity-0" style="background: linear-gradient(90deg, #d97706, #f59e0b); width: 0%;"></div>

    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
        <div class="px-3 py-2.5 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-xl lg:hidden hover:bg-charcoal-900 focus:outline-none" aria-label="Menu Navigasi">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 ml-2 md:mr-24">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-9 sm:h-10 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-xl font-bold tracking-tight text-white font-serif leading-none">Niskala<span class="text-gold-500 font-semibold ml-0.5">Samudra</span></span>
                        </div>
                    </a>
                </div>

                <div class="flex items-center">
                    <div class="relative ml-3" x-data="{ userMenu: false }">
                        <div>
                            <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-2 p-1 rounded-full hover:bg-charcoal-900 transition focus:outline-none">
                                <img class="w-8 h-8 rounded-full border border-gold-500/40 object-cover shadow-2xs"
                                     src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Admin').'&background=f59e0b&color=fff' }}" alt="Admin Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-2 text-sm bg-white divide-y divide-slate-100 rounded-xl shadow-xl border border-slate-200/80 w-52 overflow-hidden">
                            <div class="px-4 py-3 bg-slate-50/70">
                                <p class="text-sm text-charcoal-900 font-semibold truncate">{{ Auth::user()->nama ?? 'Administrator' }}</p>
                                <p class="text-xs font-normal text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@niskalasamudra.com' }}</p>
                            </div>
                            <ul class="py-1">
                                <li><a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2 text-xs sm:text-sm text-slate-700 hover:bg-slate-50 hover:text-gold-700 font-medium"><i class="fa-solid fa-user text-xs text-gold-600 w-4"></i> Profil Saya</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-xs sm:text-sm text-rose-600 hover:bg-rose-50 font-medium"><i class="fa-solid fa-right-from-bracket text-xs w-4"></i> Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 sm:pt-18 transition-transform bg-white border-r border-slate-200 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full lg:translate-x-0'">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <div>
                <!-- Mobile / Tablet Drawer Header -->
                <div class="lg:hidden flex items-center justify-between px-2 pt-2 pb-2.5 border-b border-slate-100 mb-2">
                    <span class="text-xs font-bold text-charcoal-900 uppercase tracking-wider">Navigasi Admin</span>
                    <button type="button" @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-charcoal-950 hover:bg-slate-100 transition" aria-label="Tutup Menu">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
                <ul class="space-y-1.5 font-medium text-xs sm:text-sm">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.dashboard') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-house w-4 h-4 transition {{ request()->routeIs('admin.dashboard') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <li class="pt-3 mt-3 space-y-1.5 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Master Data</p>
                    <a href="{{ route('admin.kategori.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.kategori.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-tags w-4 h-4 transition {{ request()->routeIs('admin.kategori.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Kategori</span>
                    </a>
                    <a href="{{ route('admin.menu.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.menu.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-utensils w-4 h-4 transition {{ request()->routeIs('admin.menu.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Menu Seafood</span>
                    </a>
                    <a href="{{ route('admin.slide.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.slide.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-image w-4 h-4 transition {{ request()->routeIs('admin.slide.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Slide Banner</span>
                    </a>
                    <a href="{{ route('admin.pengguna.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-users w-4 h-4 transition {{ request()->routeIs('admin.pengguna.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Pengguna</span>
                    </a>
                </li>

                <!-- LOGIKA NOTIFIKASI TRANSAKSI -->
                @php
                    $pendingAcc = \App\Models\Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
                    $notifBadge = $pendingAcc > 99 ? '99+' : $pendingAcc;
                @endphp

                <li class="pt-3 mt-3 space-y-1.5 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Operasional</p>
                    <a href="{{ route('admin.transaksi.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.transaksi.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-4 h-4 transition {{ request()->routeIs('admin.transaksi.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Transaksi</span>
                        <!-- Tampil badge merah hanya jika ada yang Menunggu ACC -->
                        @if($pendingAcc > 0)
                            <span id="admin-pending-acc-badge" data-sidebar-count="pending_acc" class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-2 text-[10px] font-bold text-white bg-rose-600 rounded-full shadow-2xs">{{ $notifBadge }}</span>
                        @else
                            <span id="admin-pending-acc-badge" data-sidebar-count="pending_acc" class="hidden inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-2 text-[10px] font-bold text-white bg-rose-600 rounded-full shadow-2xs"></span>
                        @endif
                    </a>

                    <!-- Laporan (Sudah ada route-nya di bawah nanti) -->
                    <a href="{{ route('admin.laporan.index') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('admin.laporan.*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-chart-line w-4 h-4 transition {{ request()->routeIs('admin.laporan.*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Laporan Penjualan</span>
                    </a>
                </li>

                <li class="pt-3 mt-3 space-y-1.5 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Akses Khusus</p>
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center px-3 py-2.5 text-slate-700 rounded-xl hover:bg-slate-100 group transition border border-dashed border-slate-200">
                        <i class="fa-solid fa-people-carry-box w-4 h-4 text-slate-400 group-hover:text-gold-600 transition"></i>
                        <span class="ml-3">Mode Karyawan</span>
                    </a>
                </li>
            </ul>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-600 bg-slate-50 border border-slate-200/80 hover:bg-gold-50 hover:text-gold-800 hover:border-gold-300 transition-all duration-200">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gold-600"></i>
                        <span>Lihat Website</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-charcoal-950/50 backdrop-blur-xs lg:hidden"></div>

    <div class="lg:ml-64 pt-16 sm:pt-18 pb-10 min-h-screen" id="admin-main-content" style="transition: opacity 150ms ease-in-out;">
        <main class="w-full max-w-[1600px] mx-auto">
            @yield('content')
        </main>
    </div>

    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center bg-slate-950/40 px-4 py-6 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div x-show="deleteModalOpen" x-transition class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <div>
                    <h2 id="delete-modal-title" class="text-lg font-bold text-charcoal-900">Konfirmasi Penghapusan</h2>
                    <p class="mt-1 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <button type="button" @click="deleteModalOpen = false" class="ml-auto text-slate-400 transition hover:text-slate-700" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="px-6 py-5">
                <p class="text-sm leading-relaxed text-slate-600" x-text="deleteMessage"></p>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button" @click="deleteModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    Batal
                </button>
                <button type="button" @click="deleteForm.submit()" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-600/20">
                    Hapus Data
                </button>
            </div>
        </div>
    </div>

    <div x-show="actionModalOpen" x-cloak class="fixed inset-0 z-[60] flex min-h-screen items-center justify-center bg-slate-900/40 px-4 py-6 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="action-modal-title">
        <div x-show="actionModalOpen" x-transition class="w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600"><i class="fa-solid fa-circle-question"></i></div>
                <div>
                    <h2 id="action-modal-title" class="text-lg font-bold text-charcoal-900" x-text="actionTitle"></h2>
                    <p class="mt-1 text-sm text-slate-500">Periksa kembali sebelum melanjutkan.</p>
                </div>
                <button type="button" @click="actionModalOpen = false" class="ml-auto text-slate-400 transition hover:text-slate-700" aria-label="Tutup modal"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="px-6 py-5"><p class="text-sm leading-relaxed text-slate-600" x-text="actionMessage"></p></div>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
                <button type="button" @click="actionModalOpen = false" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">Batal</button>
                <button type="button" @click="actionForm.submit()" class="rounded-lg bg-charcoal-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-charcoal-800 focus:outline-none focus:ring-4 focus:ring-charcoal-900/20">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Global Admin Alpine Data Components
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
                            if (this.cart[index].qty < maxStok) {
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

            window.searchableRelationSelect = function(options, initialValue = '') {
                return {
                    open: false,
                    search: '',
                    selected: String(initialValue || ''),
                    options: options || [],

                    filteredOptions() {
                        const query = (this.search || '').toLowerCase().trim();
                        if (!query) return this.options;
                        return this.options.filter((option) => option.label && option.label.toLowerCase().includes(query));
                    },

                    selectedLabel() {
                        return this.options.find((option) => String(option.id) === this.selected)?.label || '';
                    },

                    select(option) {
                        this.selected = String(option ? (option.id || '') : '');
                        this.open = false;
                        this.search = '';
                    },

                    clearSelection() {
                        this.selected = '';
                        this.open = false;
                        this.search = '';
                    }
                };
            };

            window.searchableFilterSelect = function(options, initialValue = '') {
                return {
                    open: false,
                    search: '',
                    selected: String(initialValue || ''),
                    options: options || [],

                    filteredOptions() {
                        const query = (this.search || '').toLowerCase().trim();
                        if (!query) return this.options;
                        return this.options.filter((option) => option.label && option.label.toLowerCase().includes(query));
                    },

                    selectedLabel() {
                        if (!this.selected) return 'Semua Kategori';
                        return this.options.find((option) => String(option.id) === this.selected)?.label || 'Semua Kategori';
                    },

                    select(optionOrId) {
                        const id = typeof optionOrId === 'object' && optionOrId !== null ? optionOrId.id : optionOrId;
                        this.selected = String(id || '');
                        this.open = false;
                        this.search = '';
                        this.$nextTick(() => {
                            const form = this.$refs.hiddenInput?.form || this.$root?.closest('form');
                            if (form) form.submit();
                        });
                    }
                };
            };

            window.customSimpleSelect = function(options, initialValue = '', placeholder = '-- Pilih --') {
                return {
                    open: false,
                    selected: String(initialValue ?? ''),
                    placeholder: placeholder,
                    options: options || [],

                    selectedOption() {
                        return this.options.find((option) => String(option.id) === this.selected);
                    },

                    selectedLabel() {
                        const found = this.selectedOption();
                        return found ? found.label : this.placeholder;
                    },

                    select(option) {
                        this.selected = String(option ? (option.id ?? '') : '');
                        this.open = false;
                    }
                };
            };

            window.customFilterSelect = function(options, initialValue = '', defaultLabel = 'Semua') {
                return {
                    open: false,
                    selected: String(initialValue ?? ''),
                    defaultLabel: defaultLabel,
                    options: options || [],

                    selectedLabel() {
                        if (!this.selected) return this.defaultLabel;
                        const found = this.options.find((option) => String(option.id) === this.selected);
                        return found ? found.label : this.defaultLabel;
                    },

                    select(optionOrId) {
                        const id = typeof optionOrId === 'object' && optionOrId !== null ? optionOrId.id : optionOrId;
                        this.selected = String(id ?? '');
                        this.open = false;
                        this.$nextTick(() => {
                            const form = this.$refs.hiddenInput?.form || this.$root?.closest('form');
                            if (form) form.submit();
                        });
                    }
                };
            };

            function registerAdminAlpineComponents() {
                if (!window.Alpine) return;
                window.Alpine.data('searchablePelangganSelect', window.searchablePelangganSelect);
                window.Alpine.data('posSystem', window.posSystem);
                window.Alpine.data('searchableRelationSelect', window.searchableRelationSelect);
                window.Alpine.data('searchableFilterSelect', window.searchableFilterSelect);
                window.Alpine.data('customSimpleSelect', window.customSimpleSelect);
                window.Alpine.data('customFilterSelect', window.customFilterSelect);
            }

            if (window.Alpine) {
                registerAdminAlpineComponents();
            } else {
                document.addEventListener('alpine:init', registerAdminAlpineComponents);
            }

            const ProgressBar = {
                el: null,
                timer: null,
                getEl() {
                    if (!this.el) this.el = document.getElementById('pjax-progress');
                    return this.el;
                },
                start() {
                    const bar = this.getEl();
                    if (!bar) return;
                    clearInterval(this.timer);
                    bar.style.opacity = '1';
                    bar.style.width = '30%';
                    let cur = 30;
                    this.timer = setInterval(() => {
                        if (cur < 85) {
                            cur += Math.random() * 12;
                            bar.style.width = Math.min(cur, 85) + '%';
                        }
                    }, 120);
                },
                done() {
                    const bar = this.getEl();
                    if (!bar) return;
                    clearInterval(this.timer);
                    bar.style.width = '100%';
                    setTimeout(() => {
                        bar.style.opacity = '0';
                        setTimeout(() => {
                            bar.style.width = '0%';
                        }, 250);
                    }, 180);
                }
            };

            async function refreshSidebarCounts() {
                try {
                    const response = await fetch('{{ route('sidebar.counts') }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin',
                    });

                    if (!response.ok) return;

                    const counts = await response.json();
                    document.querySelectorAll('[data-sidebar-count]').forEach((badge) => {
                        const count = Number(counts[badge.dataset.sidebarCount] ?? 0);
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.classList.toggle('hidden', count === 0);
                    });
                } catch (error) {
                    console.warn('Gagal memperbarui jumlah notifikasi sidebar.', error);
                }
            }

            function updateSidebarActive(pathname) {
                const clean = (p) => (p || '').replace(/\/$/, '') || '/';
                const current = clean(pathname);

                document.querySelectorAll('.sidebar-nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        const linkPath = clean(new URL(href, window.location.origin).pathname);
                        const isMatch = (linkPath === '/admin/dashboard' && (current === '/admin/dashboard' || current === '/admin')) ||
                                        (linkPath !== '/admin/dashboard' && linkPath !== '/admin' && (current === linkPath || current.startsWith(linkPath + '/')));

                        const icon = link.querySelector('i');
                        if (isMatch) {
                            link.classList.remove('text-slate-700', 'text-charcoal-900', 'hover:bg-slate-100', 'hover:text-charcoal-950');
                            link.classList.add('bg-charcoal-950', 'text-white', 'shadow-xs', 'font-medium');
                            if (icon) {
                                icon.classList.remove('text-slate-400', 'group-hover:text-gold-600', 'text-gold-500');
                                icon.classList.add('text-gold-400');
                            }

                        } else {
                            link.classList.remove('bg-charcoal-950', 'bg-charcoal-900', 'text-white', 'shadow-xs', 'shadow-md', 'font-medium');
                            link.classList.add('text-slate-700', 'hover:bg-slate-100', 'hover:text-charcoal-950');
                            if (icon) {
                                icon.classList.remove('text-gold-400', 'text-gold-500');
                                icon.classList.add('text-slate-400', 'group-hover:text-gold-600');
                            }
                        }
                    } catch (e) {}
                });
            }

            async function pjaxNavigate(url, updateHistory = true) {
                const main = document.getElementById('admin-main-content');
                if (!main) {
                    window.location.href = url;
                    return;
                }

                ProgressBar.start();
                main.style.opacity = '0.4';

                try {
                    const res = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!res.ok) throw new Error('Failed to load page');

                    const html = await res.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const newMain = doc.getElementById('admin-main-content');

                    if (newMain) {
                        if (window.Alpine && typeof window.Alpine.destroyTree === 'function') {
                            try {
                                Array.from(main.children).forEach(child => window.Alpine.destroyTree(child));
                            } catch (e) {}
                        }

                        main.innerHTML = newMain.innerHTML;
                        const newTitle = doc.querySelector('title');
                        if (newTitle) document.title = newTitle.innerText;

                        // Re-execute scripts inside content (e.g. Chart.js)
                        main.querySelectorAll('script').forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.textContent = oldScript.textContent;
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });

                        if (window.Alpine) {
                            try {
                                registerAdminAlpineComponents();
                                window.Alpine.initTree(main);
                            } catch (e) {
                                console.error('Alpine initTree error:', e);
                            }
                        }

                        if (updateHistory) {
                            window.history.pushState({ adminPjax: true }, '', url);
                        }

                        updateSidebarActive(new URL(url, window.location.origin).pathname);
                        refreshSidebarCounts();
                        window.dispatchEvent(new CustomEvent('close-sidebar'));
                        window.scrollTo({ top: 0, behavior: 'instant' });
                    } else {
                        window.location.href = url;
                    }
                } catch (e) {
                    window.location.href = url;
                } finally {
                    main.style.opacity = '1';
                    ProgressBar.done();
                }
            }

            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                if (link.origin !== window.location.origin) return;

                const href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;

                const urlObj = new URL(link.href);
                const path = urlObj.pathname;

                if (!path.startsWith('/admin')) return;
                if (path.includes('/delete') || path.includes('/destroy')) return;
                if (link.hasAttribute('data-no-pjax')) return;

                e.preventDefault();
                pjaxNavigate(link.href);
            });

            document.addEventListener('submit', (e) => {
                const form = e.target.closest('form');
                if (!form || form.method.toUpperCase() !== 'GET') return;

                const action = form.getAttribute('action') || window.location.href;
                try {
                    const actionUrl = new URL(action, window.location.origin);
                    if (actionUrl.pathname.startsWith('/admin')) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const params = new URLSearchParams();
                        for (const [key, value] of formData.entries()) {
                            if (value.trim() !== '') params.append(key, value.trim());
                        }
                        const query = params.toString();
                        const targetUrl = actionUrl.origin + actionUrl.pathname + (query ? '?' + query : '');
                        pjaxNavigate(targetUrl);
                    }
                } catch(err) {}
            });

            window.addEventListener('popstate', () => {
                if (window.location.pathname.startsWith('/admin')) {
                    pjaxNavigate(window.location.href, false);
                }
            });

            // Initial sync on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    updateSidebarActive(window.location.pathname);
                    refreshSidebarCounts();
                });
            } else {
                updateSidebarActive(window.location.pathname);
                refreshSidebarCounts();
            }

            setInterval(refreshSidebarCounts, 15000);
        })();
    </script>
</body>
</html>
