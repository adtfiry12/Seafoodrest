@extends('template.front')

@section('content')

<div class="bg-charcoal-950 py-4 border-b border-charcoal-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">
            <a href="{{ route('home') }}" class="hover:text-gold-500 transition">Beranda</a>
            <span class="mx-2">/</span>
            <a href="{{ route('menu') }}" class="hover:text-gold-500 transition">Menu</a>
            <span class="mx-2">/</span>
            <span class="text-gold-500">{{ $menu->kategori->nama_kategori ?? 'Umum' }}</span>
        </p>
    </div>
</div>

<section class="bg-white py-12 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">

            <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-100">
                @php $imageUrl = $menu->gambar ? asset('storage/' . $menu->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                <img src="{{ $imageUrl }}" alt="{{ $menu->nama_menu }}" class="w-full h-auto object-cover max-h-[500px]">
            </div>

            <div class="flex flex-col h-full">
                <span class="inline-block px-3 py-1 bg-charcoal-900 text-gold-500 text-[10px] font-bold uppercase tracking-widest rounded mb-4 w-max">
                    {{ $menu->kategori->nama_kategori ?? 'Spesial' }}
                </span>

                <h1 class="text-3xl sm:text-5xl font-serif font-bold text-charcoal-900 mb-4 leading-tight">{{ $menu->nama_menu }}</h1>
                <p class="text-3xl font-bold text-gold-600 mb-6">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>

                <div class="border-t border-b border-slate-100 py-6 mb-8">
                    <h4 class="text-sm font-bold text-charcoal-900 uppercase tracking-wider mb-3">Deskripsi Hidangan</h4>
                    <p class="text-slate-600 leading-relaxed font-light text-sm sm:text-base">
                        {{ $menu->deskripsi ?? 'Nikmati kelezatan hidangan laut spesial yang diracik khusus menggunakan bahan-bahan segar pilihan dan bumbu rempah otentik nusantara. Cocok untuk dinikmati bersama keluarga maupun kolega Anda.' }}
                    </p>
                </div>

                <div class="mb-8">
                    <p class="text-sm text-slate-500 font-medium mb-2">Ketersediaan:
                        @if($menu->stok > 10)
                            <span class="text-emerald-500 font-bold">Tersedia Banyak ({{ $menu->stok }} Porsi)</span>
                        @elseif($menu->stok > 0)
                            <span class="text-orange-500 font-bold">Terbatas (Sisa {{ $menu->stok }} Porsi)</span>
                        @else
                            <span class="text-red-500 font-bold">Habis Terjual</span>
                        @endif
                    </p>
                </div>

                @if($menu->stok > 0)
                    <button @click="addToCart({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, '{{ $imageUrl }}', {{ $menu->stok }})" class="w-full sm:w-auto px-10 py-4 bg-charcoal-900 hover:bg-gold-600 text-white font-bold rounded-xl transition duration-300 text-sm uppercase tracking-wider shadow-lg active:scale-95 flex items-center justify-center gap-3">
                        <i class="fa-solid fa-cart-plus text-lg"></i> Tambahkan ke Keranjang
                    </button>
                @else
                    <button disabled class="w-full sm:w-auto px-10 py-4 bg-slate-300 text-slate-500 font-bold rounded-xl text-sm uppercase tracking-wider cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif

            </div>
        </div>
    </div>
</section>

@if($relatedMenus->count() > 0)
<section class="bg-slate-50 py-16 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-serif text-charcoal-900 mb-8 border-l-4 border-gold-600 pl-4">Hidangan Serupa</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedMenus as $related)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col justify-between group">
                    <div class="relative overflow-hidden h-40">
                        @php $relImg = $related->gambar ? asset('storage/' . $related->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                        <img src="{{ $relImg }}" alt="{{ $related->nama_menu }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-serif font-bold text-charcoal-900 text-base truncate">{{ $related->nama_menu }}</h3>
                        <p class="text-gold-600 font-bold mt-1 text-sm">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 pt-0">
                        <a href="{{ route('menu.detail', $related->slug) }}" class="block text-center w-full bg-slate-100 hover:bg-gold-600 hover:text-white text-slate-600 font-bold py-2 rounded-lg transition duration-300 text-[10px] uppercase tracking-wider">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
