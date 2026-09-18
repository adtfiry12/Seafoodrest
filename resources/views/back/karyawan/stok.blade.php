@extends('template.back_karyawan')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Header & Filter -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-purple-50 text-purple-500 rounded-xl shrink-0">
                <i class="fa-solid fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Kelola Stok Menu</h1>
                <p class="text-sm text-slate-500 mt-1">Perbarui sisa stok agar pelanggan tidak memesan menu yang habis.</p>
            </div>
        </div>

        <!-- Form Pencarian & Kategori -->
        <form action="{{ route('karyawan.stok') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">

            <!-- Dropdown Kategori -->
            <select name="kategori" class="bg-white border border-slate-200 rounded-lg px-4 py-2 text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-sm outline-none" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <!-- Input Pencarian -->
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." class="w-full bg-white border border-slate-200 rounded-lg pl-4 pr-10 py-2 text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-sm outline-none">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
            <i class="fa-solid fa-circle-check text-emerald-500 mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Grid Menu -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($menus as $menu)
            <!-- AlpineJS dipakai disini biar tombol +/- jalan otomatis -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col transition hover:shadow-md" x-data="{ stokSekarang: {{ $menu->stok }} }">

                <!-- Gambar Menu -->
                <div class="h-28 w-full bg-slate-100 relative group overflow-hidden">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-400">
                            <i class="fa-solid fa-image text-3xl"></i>
                        </div>
                    @endif

                    <!-- Label Habis (Jika 0) -->
                    <div x-show="stokSekarang == 0" class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider shadow-sm" x-cloak>
                        Habis
                    </div>
                </div>

                <!-- Info Menu -->
                <div class="p-3 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gold-600 uppercase tracking-wider mb-1 truncate">{{ $menu->kategori->nama_kategori ?? 'Tanpa Kategori' }}</p>
                            <h3 class="font-bold text-sm text-charcoal-900 leading-tight truncate" title="{{ $menu->nama_menu }}">{{ $menu->nama_menu }}</h3>
                            <p class="text-xs text-slate-500 mt-1">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('karyawan.stok.detail', $menu->slug) }}" title="Lihat detail menu dan pesanan" aria-label="Lihat detail menu dan pesanan" class="inline-flex items-center justify-center w-8 h-8 shrink-0 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Form Update Stok -->
                <div class="p-4 bg-slate-50 border-t border-slate-100">
                    <form action="{{ route('karyawan.stok.update', $menu->id) }}" method="POST">
                        @csrf
                        <div class="flex items-center justify-between gap-3">

                            <!-- Pengatur Angka (+/-) -->
                            <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white shadow-sm flex-1">
                                <button type="button" @click="if(stokSekarang > 0) stokSekarang--" class="px-3 py-2 text-slate-500 hover:text-red-500 hover:bg-slate-50 transition outline-none">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>

                                <input type="number" name="stok" x-model="stokSekarang" class="w-full text-center text-sm font-bold text-charcoal-900 focus:outline-none border-x border-slate-100 p-0 py-2 hide-arrows" min="0" required>

                                <button type="button" @click="stokSekarang++" class="px-3 py-2 text-slate-500 hover:text-emerald-500 hover:bg-slate-50 transition outline-none">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>

                            <!-- Tombol Simpan -->
                            <button type="submit" class="px-4 py-2 bg-charcoal-900 text-white text-sm font-bold rounded-lg hover:bg-gold-600 transition shadow-sm active:scale-95 shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 text-center py-20 bg-white rounded-3xl shadow-sm border border-slate-100">
                <i class="fa-solid fa-box-open text-6xl text-slate-200 mb-4"></i>
                <h3 class="text-xl font-serif text-charcoal-900 mb-2">Menu Tidak Ditemukan</h3>
                <p class="text-slate-500">Coba ubah kata kunci pencarian atau kategori.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($menus->hasPages())
        <div class="mt-8 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm">
            {{ $menus->links() }}
        </div>
    @endif

</div>

<!-- CSS Kecil untuk menghilangkan tombol naik-turun bawaan browser di input number -->
<style>
    .hide-arrows::-webkit-outer-spin-button,
    .hide-arrows::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .hide-arrows {
        -moz-appearance: textfield;
    }
</style>
@endsection
