@extends('template.front')

@section('content')

<section class="bg-charcoal-950 py-12 border-b-4 border-gold-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl sm:text-5xl font-serif text-white mb-4">Buku Menu Kami</h1>
        <p class="text-slate-400 text-sm sm:text-base font-light max-w-2xl mx-auto">Jelajahi berbagai hidangan laut segar pilihan, disiapkan khusus oleh chef berpengalaman kami.</p>
    </div>
</section>

<section class="bg-white py-6 border-b border-slate-200 sticky top-[72px] z-30 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('menu') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <button type="submit" name="kategori" value="" class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition-all {{ request('kategori') == '' ? 'bg-gold-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua Menu</button>
                @foreach($kategori as $kat)
                    <button type="submit" name="kategori" value="{{ $kat->slug }}" class="px-4 py-2 rounded-full text-xs sm:text-sm font-bold transition-all {{ request('kategori') == $kat->slug ? 'bg-gold-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        {{ $kat->nama_kategori }}
                    </button>
                @endforeach
            </div>

            <div class="w-full md:w-80 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu favorit..." class="w-full bg-slate-50 border border-slate-300 rounded-full pl-5 pr-12 py-2.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-all text-charcoal-900">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center bg-gold-600 text-white rounded-full hover:bg-gold-500 transition">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<section class="bg-slate-50 py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($menus as $item)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col justify-between group">
                    <div>
                        <div class="relative overflow-hidden h-48 sm:h-52">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                            <span class="absolute top-3 left-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-wider rounded-lg shadow-sm">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>
                            @if($item->stok < 5)
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5 text-center">
                            <h3 class="font-serif font-bold text-charcoal-900 text-lg leading-snug truncate">{{ $item->nama_menu }}</h3>
                            <p class="text-gold-600 font-bold text-lg mt-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 grid grid-cols-2 gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" class="flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-600 font-bold py-2.5 rounded-xl transition duration-300 text-[11px] uppercase tracking-wider shadow-sm">
                            Lihat Detail
                        </a>
                        <button @click="addToCart({{ $item->id }}, '{{ addslashes($item->nama_menu) }}', {{ $item->harga }}, '{{ $imageUrl }}', {{ $item->stok }})" class="flex items-center justify-center bg-gold-600 hover:bg-gold-500 text-white font-bold py-2.5 rounded-xl transition duration-300 text-[11px] uppercase tracking-wider shadow-sm active:scale-95">
                            <i class="fa-solid fa-cart-plus mr-1.5"></i> Keranjang
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <i class="fa-solid fa-fish text-6xl text-slate-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-charcoal-900 font-serif">Menu Tidak Ditemukan</h3>
                    <p class="text-slate-500 mt-2">Coba gunakan kata kunci atau kategori yang berbeda.</p>
                </div>
            @endforelse
        </div>

        @if($menus->hasPages())
            <div class="mt-12">
                {{ $menus->links() }}
            </div>
        @endif
    </div>
</section>

@endsection
