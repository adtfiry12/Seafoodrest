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
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false, actionModalOpen: false, actionForm: null, actionTitle: '', actionMessage: '', openActionModal(event, title, message) { this.actionForm = event.currentTarget; this.actionTitle = title; this.actionMessage = message; this.actionModalOpen = true; } }">
    {{-- Global Progress Bar for Karyawan SPA Navigation --}}
    <div id="pjax-progress" class="fixed top-0 left-0 right-0 h-1 z-[100] transition-all duration-300 pointer-events-none opacity-0" style="background: linear-gradient(90deg, #d97706, #fbbf24, #f59e0b); width: 0%;"></div>

    <nav class="fixed top-0 z-50 w-full bg-charcoal-950 border-b border-charcoal-800 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center p-2 text-sm text-gold-500 rounded-lg sm:hidden hover:bg-charcoal-800 focus:outline-none">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <a href="{{ route('dashboard.karyawan') }}" class="flex items-center gap-1 ml-2 md:mr-24">
                        <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-9 sm:h-12 w-auto object-contain">
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-2xl font-bold tracking-wide text-white font-serif leading-none">Niskala<span class="text-gold-500">Samudra</span></span>
                        </div>
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="relative ml-3" x-data="{ userMenu: false }">
                        <div>
                            <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex text-sm bg-charcoal-800 rounded-full focus:ring-4 focus:ring-charcoal-700">
                                <img class="w-8 h-8 rounded-full border border-gold-500/50 object-cover"
                                     src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama ?? 'Karyawan').'&background=f59e0b&color=fff' }}" alt="Karyawan Photo">
                            </button>
                        </div>
                        <div x-show="userMenu" x-transition x-cloak class="absolute right-0 z-50 my-4 text-base bg-white divide-y divide-slate-100 rounded shadow border border-slate-200 w-48">
                            <div class="px-4 py-3">
                                <p class="text-sm text-charcoal-900 font-bold truncate">{{ Auth::user()->nama ?? 'Karyawan Resto' }}</p>
                                <p class="text-sm font-medium text-slate-500 truncate">{{ Auth::user()->email ?? 'karyawan@niskalasamudra.com' }}</p>
                            </div>
                            <ul class="py-1">
                                <li><a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-gold-600">Profil Saya</a></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-slate-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white flex flex-col justify-between">
            <ul class="space-y-2 font-medium text-sm">
                <li>
                    <a href="{{ route('dashboard.karyawan') }}" class="sidebar-nav-link flex items-center p-3 rounded-lg group transition {{ request()->routeIs('dashboard.karyawan') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-house w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard.karyawan') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Ringkasan Shift</span>
                    </a>
                </li>

                @php
                    $pendingACC = \App\Models\Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
                    $pendingDapur = \App\Models\Transaksi::where('status_pesanan', 'Dimasak')->count();
                    $pendingDelivery = \App\Models\Transaksi::whereIn('status_pesanan', ['Siap Diantar', 'Diantar'])->count();
                @endphp

                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Tugas Operasional</p>

                    <a href="{{ route('karyawan.pesanan_baru') }}" class="sidebar-nav-link flex items-center p-3 rounded-lg group transition {{ request()->routeIs('karyawan.pesanan_baru') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-bell-concierge w-5 h-5 transition {{ request()->routeIs('karyawan.pesanan_baru') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Pesanan Baru (Kasir)</span>
                        @if($pendingACC > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-3 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse">{{ $pendingACC }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.dapur') }}" class="sidebar-nav-link flex items-center p-3 rounded-lg group transition {{ request()->routeIs('karyawan.dapur') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-fire-burner w-5 h-5 transition {{ request()->routeIs('karyawan.dapur') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Stasiun Dapur</span>
                        @if($pendingDapur > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-3 text-[10px] font-bold text-charcoal-900 bg-gold-400 rounded-full">{{ $pendingDapur }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.delivery') }}" class="sidebar-nav-link flex items-center p-3 rounded-lg group transition {{ request()->routeIs('karyawan.delivery') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-motorcycle w-5 h-5 transition {{ request()->routeIs('karyawan.delivery') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3 flex-1 whitespace-nowrap">Pengantaran Delivery</span>
                        @if($pendingDelivery > 0)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ml-3 text-[10px] font-bold text-white bg-blue-500 rounded-full">{{ $pendingDelivery }}</span>
                        @endif
                    </a>

                    <a href="{{ route('karyawan.stok') }}" class="sidebar-nav-link flex items-center p-3 rounded-lg group transition mt-4 border-t border-slate-100 pt-4 {{ request()->routeIs('karyawan.stok') ? 'bg-charcoal-900 text-white shadow-md' : 'text-charcoal-900 hover:bg-slate-100' }}">
                        <i class="fa-solid fa-boxes-packing w-5 h-5 transition {{ request()->routeIs('karyawan.stok') ? 'text-gold-500' : 'text-slate-400 group-hover:text-gold-600' }}"></i>
                        <span class="ml-3">Input Stok Masuk</span>
                    </a>
                </li>

                @if(Auth::user() && Auth::user()->role == 'admin')
                <li class="pt-4 mt-4 space-y-2 border-t border-slate-200">
                    <p class="px-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Admin Mode</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 text-white rounded-lg bg-charcoal-900 hover:bg-charcoal-800 shadow-md group transition">
                        <i class="fa-solid fa-arrow-left-long w-5 h-5 text-gold-500 transition duration-75 group-hover:-translate-x-1"></i>
                        <span class="ml-3">Kembali ke Admin</span>
                    </a>
                </li>
                @endif

            </ul>

            <div class="mt-4 pt-3 border-t border-slate-200">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 bg-slate-50 border border-slate-200 hover:bg-gold-50 hover:text-gold-700 hover:border-gold-300 transition-all duration-200">
                    <span class="flex items-center gap-2.5">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gold-600"></i>
                        <span>Lihat Website</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-[10px] text-slate-400"></i>
                </a>
            </div>
        </div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-charcoal-900/50 sm:hidden"></div>

    <div class="sm:ml-64 pt-16 sm:pt-20 pb-10" id="karyawan-main-content" style="transition: opacity 150ms ease-in-out;">
        @yield('content')
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
                const clean = (p) => (p || '').replace(/\/$/, '');
                const current = clean(pathname);

                document.querySelectorAll('.sidebar-nav-link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    try {
                        const linkPath = clean(new URL(href, window.location.origin).pathname);
                        const isMatch = linkPath === current || (linkPath !== '/karyawan' && current.startsWith(linkPath));

                        const icon = link.querySelector('i');
                        if (isMatch) {
                            link.classList.add('bg-charcoal-900', 'text-white', 'shadow-md');
                            link.classList.remove('text-charcoal-900', 'hover:bg-slate-100');
                            if (icon) {
                                icon.classList.add('text-gold-500');
                                icon.classList.remove('text-slate-400', 'group-hover:text-gold-600');
                            }
                        } else {
                            link.classList.remove('bg-charcoal-900', 'text-white', 'shadow-md');
                            link.classList.add('text-charcoal-900', 'hover:bg-slate-100');
                            if (icon) {
                                icon.classList.remove('text-gold-500');
                                icon.classList.add('text-slate-400', 'group-hover:text-gold-600');
                            }
                        }
                    } catch (e) {}
                });
            }

            async function pjaxNavigate(url, updateHistory = true) {
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
                            window.Alpine.initTree(main);
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
        })();
    </script>
</body>
</html>
