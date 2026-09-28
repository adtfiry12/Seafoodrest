@extends('template.front')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-charcoal-950 py-8 sm:py-16 border-b-4 border-gold-600 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Hidangan seafood Niskala Samudra" class="h-full w-full object-cover opacity-60">
        <div class="absolute inset-0 bg-linear-to-r from-charcoal-950 via-charcoal-950/85 to-charcoal-950/70"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-gold-400 text-[10px] sm:text-xs font-semibold uppercase tracking-widest mb-2 sm:mb-3">
                <i class="fa-solid fa-utensils text-[9px] sm:text-[10px]"></i>
                <span>Katalog Hidangan</span>
            </div>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif text-white leading-tight font-bold">Menu Niskala Samudra</h1>
            <p class="text-slate-300 text-xs sm:text-base font-light mt-2 max-w-xl leading-relaxed">
                Pilihan hidangan laut segar Nusantara dengan racikan bumbu khas. Tersedia untuk santap di tempat, bawa pulang (takeaway), maupun pesan antar.
            </p>
        </div>
    </div>
</section>

{{-- Filter and Search Toolbar (Natural Page Flow, Non-Sticky) --}}
<section class="bg-white py-4 sm:py-6 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4">

            {{-- Category Filter Pills --}}
            <div class="flex items-center gap-1.5 sm:gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                <a href="{{ route('menu', array_filter(['search' => request('search')])) }}"
                   class="shrink-0 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 {{ !request('kategori') ? 'bg-charcoal-900 text-gold-400 shadow-sm ring-1 ring-charcoal-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-charcoal-900' }}">
                    Semua Menu
                </a>
                @foreach($kategori as $kat)
                    <a href="{{ route('menu', array_filter(['kategori' => $kat->slug, 'search' => request('search')])) }}"
                       class="shrink-0 px-3 py-1.5 sm:px-4 sm:py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200 {{ request('kategori') == $kat->slug ? 'bg-charcoal-900 text-gold-400 shadow-sm ring-1 ring-charcoal-900' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-charcoal-900' }}">
                        {{ $kat->nama_kategori }}
                    </a>
                @endforeach
            </div>

            {{-- Search Box --}}
            <div class="w-full lg:w-80 shrink-0">
                <form action="{{ route('menu') }}" method="GET" class="relative">
                    @if(request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari menu seafood..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-20 py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 focus:bg-white transition-all text-charcoal-900 placeholder:text-slate-400">
                        @if(request('search'))
                            <a href="{{ route('menu', array_filter(['kategori' => request('kategori')])) }}" class="absolute right-14 text-slate-400 hover:text-slate-600 p-1" title="Hapus pencarian">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </a>
                        @endif
                        <button type="submit" class="absolute right-1.5 px-3 py-1.5 bg-gold-600 hover:bg-gold-500 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition shadow-xs">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

{{-- Menu Catalog Section --}}
<section class="bg-slate-50/60 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Status / Active Filter Badge --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Daftar Pilihan</p>
                <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">
                    @if(request('kategori'))
                        Kategori {{ $kategori->firstWhere('slug', request('kategori'))->nama_kategori ?? 'Pilihan' }}
                    @else
                        Semua Hidangan
                    @endif
                </h2>
            </div>
            <div class="text-xs sm:text-sm text-slate-500">
                Menampilkan <span class="font-bold text-charcoal-900">{{ $menus->total() }}</span> hidangan tersedia
            </div>
        </div>

        {{-- Active Filter Notification --}}
        @if(request('search') || request('kategori'))
            <div class="mb-6 p-3 sm:p-4 bg-amber-50/80 border border-amber-200/90 rounded-2xl flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm">
                <div class="flex items-center gap-2 text-charcoal-800">
                    <i class="fa-solid fa-filter text-gold-600"></i>
                    <span>
                        Filter aktif:
                        @if(request('kategori'))
                            <span class="font-bold text-charcoal-900 bg-white px-2 py-0.5 rounded-md border border-amber-200 ml-1">Kategori: {{ $kategori->firstWhere('slug', request('kategori'))->nama_kategori ?? request('kategori') }}</span>
                        @endif
                        @if(request('search'))
                            <span class="font-bold text-charcoal-900 bg-white px-2 py-0.5 rounded-md border border-amber-200 ml-1">Kata kunci: "{{ request('search') }}"</span>
                        @endif
                    </span>
                </div>
                <a href="{{ route('menu') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-200 transition">
                    <i class="fa-solid fa-xmark"></i> Reset Filter
                </a>
            </div>
        @endif

        {{-- Grid Menu --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-6">
            @forelse ($menus as $item)
                <article class="bg-white rounded-2xl shadow-xs hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-200/80 flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('menu.detail', $item->slug) }}" class="block relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $item->stok <= 0 ? 'grayscale-[40%] opacity-85' : '' }}">

                            <div class="absolute inset-0 bg-linear-to-t from-charcoal-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-charcoal-950/80 backdrop-blur-md text-gold-400 text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 uppercase tracking-wider rounded-md sm:rounded-lg shadow-xs">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>

                            @if($item->stok <= 0)
                                <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-slate-900/90 text-rose-300 border border-rose-500/30 text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg shadow-xs">
                                    Habis
                                </span>
                            @elseif($item->stok < 5)
                                <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-rose-500 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg shadow-xs">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </a>

                        <div class="p-2.5 sm:p-4">
                            <a href="{{ route('menu.detail', $item->slug) }}" class="block font-serif font-bold text-charcoal-900 text-xs sm:text-base leading-snug line-clamp-2 min-h-8 sm:min-h-10 group-hover:text-gold-600 transition-colors">
                                {{ $item->nama_menu }}
                            </a>
                            <div class="mt-2 flex items-baseline justify-between">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Harga</span>
                                    <p class="text-gold-600 font-bold text-xs sm:text-base">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                                @if($item->stok > 0)
                                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-medium hidden sm:inline-block">
                                        <i class="fa-solid fa-circle-check text-[9px] sm:text-[10px] mr-0.5"></i> Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-2.5 sm:p-4 pt-0 flex items-center gap-1.5 sm:gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" aria-label="Lihat detail {{ $item->nama_menu }}" title="Detail" class="h-8 w-8 sm:h-auto sm:w-auto sm:px-3 sm:py-2 flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-700 font-bold rounded-lg sm:rounded-xl transition duration-300 text-xs shrink-0 shadow-xs">
                            <i class="fa-solid fa-eye text-xs"></i><span class="hidden sm:inline ml-1 text-xs">Detail</span>
                        </a>
                        @if($item->stok > 0)
                            <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" aria-label="Tambah {{ $item->nama_menu }} ke keranjang" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 sm:px-3 bg-gold-600 hover:bg-gold-500 text-white font-bold text-xs rounded-lg sm:rounded-xl transition flex items-center justify-center gap-1 sm:gap-1.5 shadow-xs active:scale-95 whitespace-nowrap">
                                <i class="fa-solid fa-cart-plus text-[11px] sm:text-xs"></i> <span>+ Pesan</span>
                            </button>
                        @else
                            <button disabled aria-label="Menu habis" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-lg sm:rounded-xl cursor-not-allowed border border-slate-200 flex items-center justify-center gap-1 whitespace-nowrap">
                                <i class="fa-solid fa-ban text-[11px]"></i> <span>Habis</span>
                            </button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-3xl border border-slate-200/80 p-10 sm:p-16 text-center shadow-xs">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-amber-50 text-gold-600 flex items-center justify-center text-2xl sm:text-3xl mb-4">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900 mb-2">Menu Tidak Ditemukan</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto mb-6">
                        @if(request('search'))
                            Tidak ada menu yang sesuai dengan kata kunci "<span class="font-semibold text-charcoal-800">{{ request('search') }}</span>".
                        @else
                            Belum ada menu yang tersedia untuk kategori yang dipilih saat ini.
                        @endif
                    </p>
                    <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-charcoal-900 hover:bg-gold-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition shadow-md">
                        <i class="fa-solid fa-rotate-left"></i> Lihat Semua Menu
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

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { scrollbar-width: none; }
</style>

@endsection
