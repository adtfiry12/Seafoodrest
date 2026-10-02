@extends('template.front')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-charcoal-950 py-8 sm:py-14 border-b border-charcoal-800 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Hidangan seafood Niskala Samudra" class="h-full w-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-r from-charcoal-950 via-charcoal-950/85 to-charcoal-950/70"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/20 text-gold-400 text-xs font-medium tracking-wide mb-2 sm:mb-3">
                <i class="fa-solid fa-utensils text-[10px]"></i>
                <span>Katalog Hidangan</span>
            </div>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif text-white leading-tight font-bold">Menu Niskala Samudra</h1>
            <p class="text-slate-300 text-xs sm:text-sm font-normal mt-2 max-w-xl leading-relaxed">
                Pilihan hidangan laut segar Nusantara dengan racikan bumbu khas. Tersedia untuk santap di tempat, bawa pulang (takeaway), maupun pesan antar.
            </p>
        </div>
    </div>
</section>

<div id="menu-catalog-root">
{{-- Filter and Search Toolbar (Natural Page Flow, Non-Sticky) --}}
<section class="bg-white py-4 sm:py-5 border-b border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">

            {{-- Category Filter Pills (Mouse Drag-to-Scroll on Desktop & Native Touch on Mobile) --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0 cursor-grab active:cursor-grabbing select-none"
                 x-data="{
                     isDown: false,
                     startX: 0,
                     scrollLeft: 0,
                     hasMoved: false,
                     init() {
                         const activeEl = this.$el.querySelector('.is-active-pill');
                         if (activeEl) {
                             activeEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                         }
                     },
                     startDrag(e) {
                         if (e.button !== 0) return;
                         this.isDown = true;
                         this.hasMoved = false;
                         this.startX = e.pageX - this.$el.offsetLeft;
                         this.scrollLeft = this.$el.scrollLeft;
                     },
                     stopDrag() {
                         this.isDown = false;
                         setTimeout(() => { this.hasMoved = false; }, 60);
                     },
                     onDrag(e) {
                         if (!this.isDown) return;
                         e.preventDefault();
                         const x = e.pageX - this.$el.offsetLeft;
                         const walk = (x - this.startX) * 1.4;
                         if (Math.abs(walk) > 4) {
                             this.hasMoved = true;
                         }
                         this.$el.scrollLeft = this.scrollLeft - walk;
                     },
                     handleItemClick(e) {
                         if (this.hasMoved) {
                             e.preventDefault();
                             e.stopPropagation();
                         }
                     }
                 }"
                 @mousedown="startDrag($event)"
                 @mouseleave="stopDrag()"
                 @mouseup="stopDrag()"
                 @mousemove="onDrag($event)">
                <a href="{{ route('menu', array_filter(['search' => request('search')])) }}"
                   @click="handleItemClick($event)"
                   draggable="false"
                   class="shrink-0 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 select-none {{ !request('kategori') ? 'is-active-pill bg-charcoal-950 text-gold-400 font-semibold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80' }}">
                    Semua Menu
                </a>
                @foreach($kategori as $kat)
                    <a href="{{ route('menu', array_filter(['kategori' => $kat->slug, 'search' => request('search')])) }}"
                       @click="handleItemClick($event)"
                       draggable="false"
                       class="shrink-0 px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-medium transition-all duration-200 select-none {{ request('kategori') == $kat->slug ? 'is-active-pill bg-charcoal-950 text-gold-400 font-semibold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200/80' }}">
                        {{ $kat->nama_kategori }}
                    </a>
                @endforeach
            </div>

            {{-- Search Box --}}
            <div class="w-full lg:w-80 shrink-0" x-data="{ query: @js(request('search', '')) }">
                <form action="{{ route('menu') }}" method="GET" class="relative">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <div class="relative flex items-center">
                        <input type="text"
                               name="search"
                               x-model="query"
                               placeholder="Cari menu seafood..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-18 py-2.5 text-xs sm:text-sm focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500 transition text-charcoal-900 placeholder:text-slate-400 shadow-2xs outline-none">

                        <div class="absolute right-1.5 inset-y-0 flex items-center gap-1">
                            @if(request('search'))
                                <a href="{{ route('menu', array_filter(['kategori' => request('kategori')])) }}"
                                   class="w-7 h-7 rounded-lg bg-slate-200/80 hover:bg-rose-100 text-slate-600 hover:text-rose-600 flex items-center justify-center transition shadow-2xs"
                                   title="Hapus pencarian">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </a>
                            @else
                                <button type="button"
                                        x-show="query && query.length > 0"
                                        x-cloak
                                        @click="query = ''"
                                        class="w-7 h-7 rounded-lg bg-slate-200/80 hover:bg-rose-100 text-slate-600 hover:text-rose-600 flex items-center justify-center transition shadow-2xs"
                                        title="Hapus ketikan">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            @endif

                            <button type="submit"
                                    class="w-7 h-7 rounded-lg bg-gold-600 hover:bg-gold-500 text-white flex items-center justify-center transition shadow-2xs"
                                    title="Cari hidangan">
                                <i class="fa-solid fa-magnifying-glass text-[11px]"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- Menu Catalog Section --}}
<section class="bg-slate-50/70 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @php
            $activeKategori = request('kategori') ? $kategori->firstWhere('slug', request('kategori')) : null;
            $hasFilter = request('search') || request('kategori');
        @endphp

        {{-- Header Status --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gold-600 block mb-1">
                    @if(request('search'))
                        Hasil Pencarian
                    @elseif($activeKategori)
                        Kategori Menu
                    @else
                        Daftar Pilihan
                    @endif
                </span>
                <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-950">
                    @if(request('search') && $activeKategori)
                        {{ $activeKategori->nama_kategori }} <span class="text-slate-400 font-sans font-normal text-lg sm:text-xl">&bull;</span> "{{ request('search') }}"
                    @elseif(request('search'))
                        Pencarian "{{ request('search') }}"
                    @elseif($activeKategori)
                        {{ $activeKategori->nama_kategori }}
                    @else
                        Semua Hidangan
                    @endif
                </h2>
            </div>
            <div class="text-xs sm:text-sm text-slate-500">
                Menampilkan <span class="font-semibold text-charcoal-900">{{ $menus->total() }}</span> hidangan tersedia
            </div>
        </div>

        {{-- Sleek, Minimal Filter Chips (No Ugly Yellow Alert Banner) --}}
        @if($hasFilter)
            <div class="flex flex-wrap items-center gap-2 mb-6 text-xs">
                <span class="text-slate-400 font-medium mr-0.5 flex items-center gap-1.5">
                    <i class="fa-solid fa-sliders text-[11px] text-slate-400"></i> Filter aktif:
                </span>

                @if($activeKategori)
                    <a href="{{ route('menu', array_filter(['search' => request('search')])) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-rose-50 text-charcoal-800 hover:text-rose-600 border border-slate-200/90 hover:border-rose-200 rounded-full text-xs font-medium transition shadow-2xs group"
                       title="Hapus filter kategori {{ $activeKategori->nama_kategori }}">
                        <span class="text-slate-400 group-hover:text-rose-500 text-[11px]">Kategori:</span>
                        <span class="font-semibold">{{ $activeKategori->nama_kategori }}</span>
                        <i class="fa-solid fa-xmark text-[10px] text-slate-400 group-hover:text-rose-600 ml-0.5"></i>
                    </a>
                @endif

                @if(request('search'))
                    <a href="{{ route('menu', array_filter(['kategori' => request('kategori')])) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1 bg-white hover:bg-rose-50 text-charcoal-800 hover:text-rose-600 border border-slate-200/90 hover:border-rose-200 rounded-full text-xs font-medium transition shadow-2xs group"
                       title="Hapus filter kata kunci">
                        <span class="text-slate-400 group-hover:text-rose-500 text-[11px]">Kata kunci:</span>
                        <span class="font-semibold font-serif">"{{ request('search') }}"</span>
                        <i class="fa-solid fa-xmark text-[10px] text-slate-400 group-hover:text-rose-600 ml-0.5"></i>
                    </a>
                @endif

                <a href="{{ route('menu') }}"
                   class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-rose-600 hover:underline underline-offset-4 font-medium transition ml-1 py-1">
                    <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset semua
                </a>
            </div>
        @endif

        {{-- Grid Menu --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-6">
            @forelse ($menus as $item)
                <article class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-200/70 flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('menu.detail', $item->slug) }}" class="block relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $item->stok <= 0 ? 'grayscale-[40%] opacity-85' : '' }}">

                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <span class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 bg-charcoal-950/75 backdrop-blur-xs text-gold-400 text-[9px] sm:text-[10px] font-medium px-2 py-0.5 rounded-md shadow-xs">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>

                            @if($item->stok <= 0)
                                <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-slate-900/90 text-rose-300 border border-rose-500/30 text-[9px] sm:text-[10px] font-medium px-2 py-0.5 rounded-md shadow-xs">
                                    Habis
                                </span>
                            @elseif($item->stok < 5)
                                <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-md shadow-xs">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </a>

                        <div class="p-3 sm:p-4">
                            <a href="{{ route('menu.detail', $item->slug) }}" class="block font-serif font-bold text-charcoal-950 text-xs sm:text-base leading-snug line-clamp-2 min-h-8 sm:min-h-10 group-hover:text-gold-700 transition-colors">
                                {{ $item->nama_menu }}
                            </a>
                            <div class="mt-2.5 flex items-baseline justify-between">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-medium text-slate-400 block">Harga</span>
                                    <p class="text-gold-700 font-bold text-xs sm:text-base">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                                @if($item->stok > 0)
                                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-medium hidden sm:inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 pt-0 flex items-center gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" aria-label="Lihat detail {{ $item->nama_menu }}" title="Detail" class="h-8 w-8 sm:h-auto sm:w-auto sm:px-3 sm:py-2 flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-700 font-medium rounded-xl transition duration-200 text-xs shrink-0 shadow-2xs">
                            <i class="fa-solid fa-eye text-xs"></i><span class="hidden sm:inline ml-1 text-xs">Detail</span>
                        </a>
                        @if($item->stok > 0)
                            <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" aria-label="Tambah {{ $item->nama_menu }} ke keranjang" class="flex-1 h-8 sm:h-auto sm:py-2 px-2.5 sm:px-3 bg-gold-600 hover:bg-gold-500 text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-2xs active:scale-95 whitespace-nowrap">
                                <i class="fa-solid fa-cart-plus text-[11px]"></i> <span>+ Pesan</span>
                            </button>
                        @else
                            <button disabled aria-label="Menu habis" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 bg-slate-100 text-slate-400 font-medium text-xs rounded-xl cursor-not-allowed border border-slate-200/80 flex items-center justify-center gap-1 whitespace-nowrap">
                                <i class="fa-solid fa-ban text-[11px]"></i> <span>Habis</span>
                            </button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200/80 p-8 sm:p-12 text-center shadow-xs">
                    <div class="w-14 h-14 mx-auto rounded-full bg-amber-50 text-gold-600 flex items-center justify-center text-2xl mb-3">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-serif font-bold text-charcoal-900 mb-1">Menu Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-xs sm:text-sm max-w-md mx-auto mb-5">
                        @if(request('search'))
                            Tidak ada menu yang sesuai dengan kata kunci "<span class="font-semibold text-charcoal-800">{{ request('search') }}</span>".
                        @else
                            Belum ada menu yang tersedia untuk kategori yang dipilih saat ini.
                        @endif
                    </p>
                    <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-charcoal-900 hover:bg-gold-600 text-white text-xs font-semibold uppercase tracking-wider rounded-xl transition shadow-xs">
                        <i class="fa-solid fa-rotate-left text-xs"></i> Lihat Semua Menu
                    </a>
                </div>
            @endforelse
        </div>

        @if($menus->hasPages())
            <div class="mt-10 sm:mt-12 flex justify-center">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</section>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { scrollbar-width: none; }
</style>

@endsection
