@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-3.5 border-b border-charcoal-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs text-slate-400 font-medium tracking-wide">
            <a href="{{ route('home') }}" class="hover:text-gold-400 transition">Beranda</a>
            <span class="mx-2 text-slate-600">/</span>
            <a href="{{ route('menu') }}" class="hover:text-gold-400 transition">Menu</a>
            <span class="mx-2 text-slate-600">/</span>
            <span class="text-gold-400">{{ $menu->kategori->nama_kategori ?? 'Umum' }}</span>
        </p>
    </div>
</div>

<section class="bg-white py-8 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14 items-start">

            <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-200/80 bg-slate-100">
                @php $imageUrl = $menu->gambar ? asset('storage/' . $menu->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                <img src="{{ $imageUrl }}" alt="{{ $menu->nama_menu }}" class="w-full h-auto object-cover max-h-72 sm:max-h-125">
            </div>

            <div class="flex flex-col h-full lg:py-2">
                <span class="inline-block px-3 py-1 bg-charcoal-900 text-gold-400 text-xs font-semibold tracking-wider uppercase rounded-md mb-3 w-max">
                    {{ $menu->kategori->nama_kategori ?? 'Spesial' }}
                </span>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-charcoal-950 mb-2 leading-tight">{{ $menu->nama_menu }}</h1>
                <p class="text-2xl sm:text-3xl font-bold text-gold-700 mb-4 sm:mb-6">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>

                <div class="border-t border-b border-slate-100 py-4 sm:py-5 mb-5 sm:mb-6">
                    <h4 class="text-xs sm:text-sm font-semibold text-charcoal-900 uppercase tracking-wider mb-2">Deskripsi Hidangan</h4>
                    <p class="text-slate-600 leading-relaxed font-normal text-xs sm:text-sm">
                        {{ $menu->deskripsi ?? 'Nikmati kelezatan hidangan laut spesial yang diracik khusus menggunakan bahan-bahan segar pilihan dan bumbu rempah otentik nusantara. Cocok untuk dinikmati bersama keluarga maupun kolega Anda.' }}
                    </p>
                </div>

                <div class="mb-6 sm:mb-8">
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">Ketersediaan:
                        @if($menu->stok > 10)
                            <span class="text-emerald-600 font-semibold ml-1">Tersedia ({{ $menu->stok }} Porsi)</span>
                        @elseif($menu->stok > 0)
                            <span class="text-amber-600 font-semibold ml-1">Terbatas (Sisa {{ $menu->stok }} Porsi)</span>
                        @else
                            <span class="text-rose-600 font-semibold ml-1">Habis Terjual</span>
                        @endif
                    </p>
                </div>

                @if($menu->stok > 0)
                    <button @click="addToCart({{ $menu->id }}, @js($menu->nama_menu), {{ $menu->harga }}, @js($imageUrl), {{ $menu->stok }})" class="w-full sm:w-auto px-7 py-3.5 bg-charcoal-950 hover:bg-gold-600 text-white font-semibold rounded-xl transition duration-200 text-xs sm:text-sm uppercase tracking-wider shadow-sm active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-plus text-sm"></i> Tambahkan ke Keranjang
                    </button>
                @else
                    <button disabled class="w-full sm:w-auto px-7 py-3.5 bg-slate-200 text-slate-400 font-semibold rounded-xl text-xs sm:text-sm uppercase tracking-wider cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif

            </div>
        </div>
    </div>
</section>

@if($relatedMenus->count() > 0)
<section class="bg-slate-50/70 py-10 sm:py-16 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8 border-l-3 border-gold-600 pl-3.5">
            <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase block">Rekomendasi</span>
            <h2 class="text-xl sm:text-2xl font-serif text-charcoal-950 font-bold mt-0.5">Hidangan Serupa</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach($relatedMenus as $related)
                <article class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-200/70 flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('menu.detail', $related->slug) }}" class="block relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $relImg = $related->gambar ? asset('storage/' . $related->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $relImg }}" alt="{{ $related->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $related->stok <= 0 ? 'grayscale-[40%] opacity-85' : '' }}">
                        </a>
                        <div class="p-3 sm:p-4">
                            <a href="{{ route('menu.detail', $related->slug) }}" class="block font-serif font-bold text-charcoal-950 text-xs sm:text-base leading-snug line-clamp-2 min-h-8 sm:min-h-10 group-hover:text-gold-700 transition-colors">
                                {{ $related->nama_menu }}
                            </a>
                            <div class="mt-2">
                                <span class="text-[9px] sm:text-[10px] font-medium text-slate-400 block">Harga</span>
                                <p class="text-gold-700 font-bold text-xs sm:text-base">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 pt-0">
                        <a href="{{ route('menu.detail', $related->slug) }}" class="block text-center w-full bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-700 font-medium py-2 rounded-xl transition duration-200 text-xs uppercase tracking-wider shadow-2xs">
                            Lihat Detail
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
