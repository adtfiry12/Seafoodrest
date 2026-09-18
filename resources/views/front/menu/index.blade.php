@extends('template.front')

@section('content')

<section class="relative overflow-hidden bg-charcoal-950 py-14 sm:py-20 border-b-4 border-gold-600">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Hidangan seafood Niskala Samudra" class="h-full w-full object-cover opacity-75">
        <div class="absolute inset-0 bg-charcoal-950/45"></div>
    </div>
    <div class="absolute -right-16 -top-20 text-[14rem] leading-none text-white/3 pointer-events-none">
        <i class="fa-solid fa-fish"></i>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-gold-500 text-[11px] sm:text-xs font-bold uppercase tracking-[0.24em] mb-3">Niskala Samudra</p>
            <h1 class="text-4xl sm:text-6xl font-serif text-white leading-tight">Pilih hidangan favoritmu</h1>
            <p class="text-slate-400 text-sm sm:text-base font-light mt-3 max-w-xl">Menu seafood segar pilihan untuk makan di tempat, takeaway, atau delivery.</p>
        </div>
    </div>
</section>

<section class="bg-white py-4 sm:py-5 border-b border-slate-200 sticky top-18 z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('menu') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-3 sm:gap-4">

            <div class="flex gap-2 w-full lg:flex-1 overflow-x-auto pb-1 scrollbar-hide">
                <button type="submit" name="kategori" value="" class="shrink-0 px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition-all {{ request('kategori') == '' ? 'bg-gold-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua Menu</button>
                @foreach($kategori as $kat)
                    <button type="submit" name="kategori" value="{{ $kat->slug }}" class="shrink-0 px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition-all {{ request('kategori') == $kat->slug ? 'bg-gold-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $kat->nama_kategori }}
                    </button>
                @endforeach
            </div>

            <div class="w-full lg:w-72 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu..." class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-4 pr-12 py-2.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-all text-charcoal-900">
                <button type="submit" aria-label="Cari menu" class="absolute right-1 top-1 w-9 h-9 flex items-center justify-center bg-gold-600 text-white rounded-lg hover:bg-gold-500 transition">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<section class="bg-slate-50 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4 mb-5 sm:mb-7">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Koleksi menu</p>
                <h2 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900 mt-1">Temukan menu pilihan</h2>
            </div>
            <p class="hidden sm:block text-sm text-slate-500">{{ $menus->total() }} menu tersedia</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5">
            @forelse ($menus as $item)
                <article class="bg-white rounded-xl sm:rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-200 flex flex-col justify-between group">
                    <div>
                        <div class="relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-charcoal-900/85 backdrop-blur-sm text-white text-[9px] sm:text-[10px] font-bold px-2 sm:px-3 py-1 sm:py-1.5 uppercase tracking-wider rounded-md sm:rounded-lg shadow-sm">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>
                            @if($item->stok < 5)
                                <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-red-500 text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-1 rounded shadow-sm">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-serif font-bold text-charcoal-900 text-sm sm:text-base leading-snug line-clamp-2 min-h-10">{{ $item->nama_menu }}</h3>
                            <p class="text-gold-600 font-bold text-sm sm:text-base mt-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 pt-0 grid grid-cols-[auto_1fr] gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" aria-label="Lihat detail {{ $item->nama_menu }}" class="flex items-center justify-center w-10 h-10 sm:w-auto sm:h-auto sm:px-3 bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-600 font-bold rounded-lg sm:rounded-xl transition duration-300 text-[11px] uppercase tracking-wider shadow-sm">
                            <i class="fa-solid fa-arrow-up-right-from-square sm:mr-1.5"></i><span class="hidden sm:inline">Detail</span>
                        </a>
                        <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" aria-label="Tambah {{ $item->nama_menu }} ke keranjang" class="flex items-center justify-center gap-1.5 bg-gold-600 hover:bg-gold-500 text-white font-bold min-h-10 rounded-lg sm:rounded-xl transition duration-300 text-[10px] sm:text-[11px] uppercase tracking-wider shadow-sm active:scale-95">
                            <i class="fa-solid fa-cart-plus"></i><span>Tambah</span>
                        </button>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-20">
                    <i class="fa-solid fa-fish text-6xl text-slate-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-charcoal-900 font-serif">Menu Tidak Ditemukan</h3>
                    <p class="text-slate-500 mt-2">Coba gunakan kata kunci atau kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        @if($menus->hasPages())
            <div class="mt-8 sm:mt-10">
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
