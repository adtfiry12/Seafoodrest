<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - Niskala Samudra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false, actionModalOpen: false, actionForm: null, actionTitle: '', actionMessage: '', openActionModal(event, title, message) { this.actionForm = event.currentTarget; this.actionTitle = title; this.actionMessage = message; this.actionModalOpen = true; } }">
    {{-- Global Progress Bar for Karyawan SPA Navigation --}}
    <div id="pjax-progress" class="fixed top-0 left-0 right-0 h-[2px] z-[100] transition-all duration-300 pointer-events-none opacity-0" style="background: linear-gradient(90deg, #d97706, #f59e0b); width: 0%;"></div>

    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-[0_1px_3px_rgba(0,0,0,0.1)]">
        <div class="px-3 py-2.5 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-xl sm:hidden hover:bg-charcoal-900 focus:outline-none">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center gap-2 ml-2 md:mr-24">
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
                                     src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Karyawan').'&background=f59e0b&color=fff' }}" alt="Karyawan Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-2 text-sm bg-white divide-y divide-slate-100 rounded-xl shadow-xl border border-slate-200/80 w-52 overflow-hidden">
                            <div class="px-4 py-3 bg-slate-50/70">
                                <p class="text-sm text-charcoal-900 font-semibold truncate">{{ Auth::user()->nama ?? 'Karyawan Resto' }}</p>
                                <p class="text-xs font-normal text-slate-500 truncate">{{ Auth::user()->email ?? 'karyawan@niskalasamudra.com' }}</p>
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

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 sm:pt-18 transition-transform bg-white border-r border-slate-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-1.5 font-medium text-xs sm:text-sm">
                <li>
                    <a href="{{ route('dashboard.karyawan') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('dashboard.karyawan') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-house w-4 h-4 transition duration-75 {{ request()->routeIs('dashboard.karyawan') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Ringkasan Shift</span>
                    </a>
                </li>

                @php
                    $pendingACC = \App\Models\Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
                    $pendingDapur = \App\Models\Transaksi::where('status_pesanan', 'Dimasak')->count();
                    $pendingDelivery = \App\Models\Transaksi::whereIn('status_pesanan', ['Siap Diantar', 'Diantar'])->count();
                @endphp

                <li class="pt-3 mt-3 space-y-1.5 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Tugas Operasional</p>

                    <a href="{{ route('karyawan.keranjang') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('karyawan.pos*') || request()->routeIs('karyawan.keranjang*') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-cash-register w-4 h-4 transition {{ request()->routeIs('karyawan.pos*') || request()->routeIs('karyawan.keranjang*') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Kasir</span>
                    </a>

                    <a href="{{ route('karyawan.pesanan_baru') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('karyawan.pesanan_baru') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-bell-concierge w-4 h-4 transition {{ request()->routeIs('karyawan.pesanan_baru') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Pesanan Masuk</span>
                        @if($pendingACC > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-2 text-[10px] font-bold text-white bg-rose-600 rounded-full shadow-2xs">{{ $pendingACC }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.dapur') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('karyawan.dapur') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-fire-burner w-4 h-4 transition {{ request()->routeIs('karyawan.dapur') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Stasiun Dapur</span>
                        @if($pendingDapur > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-2 text-[10px] font-bold text-charcoal-900 bg-gold-400 rounded-full shadow-2xs">{{ $pendingDapur }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.delivery') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition {{ request()->routeIs('karyawan.delivery') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-motorcycle w-4 h-4 transition {{ request()->routeIs('karyawan.delivery') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Pengantaran Delivery</span>
                        @if($pendingDelivery > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-2 text-[10px] font-bold text-white bg-sky-600 rounded-full shadow-2xs">{{ $pendingDelivery }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.stok') }}" class="sidebar-nav-link flex items-center px-3 py-2.5 rounded-xl group transition mt-3 border-t border-slate-100 pt-3 {{ request()->routeIs('karyawan.stok') ? 'bg-charcoal-950 text-white shadow-xs font-medium' : 'text-slate-700 hover:bg-slate-100 hover:text-charcoal-950' }}">
                        <i class="fa-solid fa-boxes-packing w-4 h-4 transition {{ request()->routeIs('karyawan.stok') ? 'text-gold-400' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Input Stok Masuk</span>
                    </a>
                </li>

                @if(Auth::user() && Auth::user()->role == 'admin')
                <li class="pt-3 mt-3 space-y-1.5 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Admin Mode</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-white rounded-xl bg-charcoal-950 hover:bg-charcoal-900 shadow-xs group transition">
                        <i class="fa-solid fa-arrow-left-long w-4 h-4 text-gold-400 transition duration-75 group-hover:-translate-x-0.5"></i>
                        <span class="ml-3">Kembali ke Admin</span>
                    </a>
                </li>
                @endif

            </ul>

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

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-charcoal-950/50 sm:hidden"></div>

    <div class="sm:ml-64 pt-16 sm:pt-18 pb-10" id="karyawan-main-content" style="transition: opacity 150ms ease-in-out;">
        @yield('content')
    </div>

    <div x-show="actionModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="action-modal-title">
        <div x-show="actionModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @click.outside="actionModalOpen = false"
             class="w-full max-w-md my-auto overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start gap-3.5 sm:gap-4 border-b border-slate-100 px-5 sm:px-6 py-4 sm:py-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                     :class="{
                         'bg-rose-50 text-rose-600 border border-rose-200/80': actionTitle.toLowerCase().includes('batal') || actionTitle.toLowerCase().includes('hapus'),
                         'bg-emerald-50 text-emerald-600 border border-emerald-200/80': actionTitle.toLowerCase().includes('terima') || actionTitle.toLowerCase().includes('lunas') || actionTitle.toLowerCase().includes('valid') || actionTitle.toLowerCase().includes('selesai'),
                         'bg-gold-50 text-gold-600 border border-gold-200/80': !actionTitle.toLowerCase().includes('batal') && !actionTitle.toLowerCase().includes('hapus') && !actionTitle.toLowerCase().includes('terima') && !actionTitle.toLowerCase().includes('lunas') && !actionTitle.toLowerCase().includes('valid') && !actionTitle.toLowerCase().includes('selesai')
                     }">
                    <i class="text-lg"
                       :class="{
                           'fa-solid fa-triangle-exclamation': actionTitle.toLowerCase().includes('batal') || actionTitle.toLowerCase().includes('hapus'),
                           'fa-solid fa-circle-check': actionTitle.toLowerCase().includes('terima') || actionTitle.toLowerCase().includes('lunas') || actionTitle.toLowerCase().includes('valid') || actionTitle.toLowerCase().includes('selesai'),
                           'fa-solid fa-bell-concierge': !actionTitle.toLowerCase().includes('batal') && !actionTitle.toLowerCase().includes('hapus') && !actionTitle.toLowerCase().includes('terima') && !actionTitle.toLowerCase().includes('lunas') && !actionTitle.toLowerCase().includes('valid') && !actionTitle.toLowerCase().includes('selesai')
                       }"></i>
                </div>
                <div class="flex-1 min-w-0 pr-2">
                    <h2 id="action-modal-title" class="text-base sm:text-lg font-serif font-bold text-charcoal-950 leading-snug" x-text="actionTitle"></h2>
                    <p class="mt-0.5 text-xs text-slate-500">Konfirmasi tindakan operasional restoran.</p>
                </div>
                <button type="button" @click="actionModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg transition" aria-label="Tutup modal">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
            <div class="px-5 sm:px-6 py-4 sm:py-5">
                <p class="text-xs sm:text-sm leading-relaxed text-slate-600" x-text="actionMessage"></p>
            </div>
            <div class="flex flex-col-reverse gap-2.5 sm:gap-3 border-t border-slate-100 bg-slate-50/80 px-5 sm:px-6 py-3.5 sm:py-4 sm:flex-row sm:justify-end">
                <button type="button" @click="actionModalOpen = false" class="w-full sm:w-auto rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-charcoal-900 shadow-2xs">
                    Batal
                </button>
                <button type="button"
                        @click="if (actionForm) { const f = actionForm; actionModalOpen = false; HTMLFormElement.prototype.submit.call(f); }"
                        class="w-full sm:w-auto rounded-xl px-5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs transition active:scale-95 flex items-center justify-center gap-2"
                        :class="{
                            'bg-rose-600 hover:bg-rose-700': actionTitle.toLowerCase().includes('batal') || actionTitle.toLowerCase().includes('hapus'),
                            'bg-emerald-600 hover:bg-emerald-700': actionTitle.toLowerCase().includes('terima') || actionTitle.toLowerCase().includes('lunas') || actionTitle.toLowerCase().includes('valid'),
                            'bg-charcoal-950 hover:bg-charcoal-900': !actionTitle.toLowerCase().includes('batal') && !actionTitle.toLowerCase().includes('hapus') && !actionTitle.toLowerCase().includes('terima') && !actionTitle.toLowerCase().includes('lunas') && !actionTitle.toLowerCase().includes('valid')
                        }">
                    <span>Lanjutkan</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <script>
        (function() {
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

            function updateSidebarActive(pathname) {
                const clean = (p) => (p || '').replace(/\/$/, '') || '/';
                const current = clean(pathname);

                document.querySelectorAll('.sidebar-nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        const linkPath = clean(new URL(href, window.location.origin).pathname);
                        const isMatch = (linkPath === '/karyawan/dashboard' && (current === '/karyawan/dashboard' || current === '/karyawan')) ||
                                        (linkPath !== '/karyawan/dashboard' && linkPath !== '/karyawan' && (current === linkPath || current.startsWith(linkPath + '/')));

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

            // --- Global Karyawan Auto-Refresh Manager ---
            window.__karyawanAutoRefreshTimer = null;

            window.stopKaryawanAutoRefresh = function() {
                if (window.__karyawanAutoRefreshTimer) {
                    clearTimeout(window.__karyawanAutoRefreshTimer);
                    window.__karyawanAutoRefreshTimer = null;
                }
            };

            window.scheduleKaryawanAutoRefresh = function(seconds = 30) {
                window.stopKaryawanAutoRefresh();

                // Pastikan TIDAK PERNAH auto-refresh di halaman Kasir / Keranjang
                const currentPath = window.location.pathname;
                if (currentPath.includes('/keranjang') || currentPath.includes('/pos')) {
                    return;
                }

                window.__karyawanAutoRefreshTimer = setTimeout(function() {
                    const path = window.location.pathname;
                    if (path.includes('/keranjang') || path.includes('/pos')) {
                        return;
                    }

                    // Jangan refresh jika user sedang aktif mengetik di input/textarea/select atau ada modal terbuka
                    const activeEl = document.activeElement;
                    const isTyping = activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.tagName === 'SELECT');
                    const hasOpenModal = !!document.querySelector('[aria-modal="true"]:not([style*="display: none"])');

                    if (isTyping || hasOpenModal) {
                        window.scheduleKaryawanAutoRefresh(15);
                        return;
                    }

                    if (typeof pjaxNavigate === 'function') {
                        pjaxNavigate(window.location.href, false);
                    } else {
                        window.location.reload();
                    }
                }, seconds * 1000);
            };

            async function pjaxNavigate(url, updateHistory = true) {
                window.stopKaryawanAutoRefresh();
                const main = document.getElementById('karyawan-main-content');
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
                    const newMain = doc.getElementById('karyawan-main-content');

                    if (newMain) {
                        if (window.Alpine && typeof window.Alpine.destroyTree === 'function') {
                            try {
                                window.Alpine.destroyTree(main);
                            } catch (e) {}
                        }

                        main.innerHTML = newMain.innerHTML;
                        const newTitle = doc.querySelector('title');
                        if (newTitle) document.title = newTitle.innerText;

                        // Re-execute scripts inside content
                        main.querySelectorAll('script').forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.textContent = oldScript.textContent;
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });

                        if (window.Alpine) {
                            try {
                                window.Alpine.initTree(main);
                            } catch (e) {
                                console.error('Alpine initTree error:', e);
                            }
                        }

                        if (updateHistory) {
                            window.history.pushState({ karyawanPjax: true }, '', url);
                        }

                        updateSidebarActive(new URL(url, window.location.origin).pathname);
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

                if (!path.startsWith('/karyawan')) return;
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
                    if (actionUrl.pathname.startsWith('/karyawan')) {
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
                if (window.location.pathname.startsWith('/karyawan')) {
                    pjaxNavigate(window.location.href, false);
                }
            });

            // Initial sync on page load
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => updateSidebarActive(window.location.pathname));
            } else {
                updateSidebarActive(window.location.pathname);
            }
        })();
    </script>
</body>
</html>
