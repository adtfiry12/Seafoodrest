@extends('template.back_admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <i class="fa-solid fa-utensils text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Menu</p>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $menu->nama_menu }}</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.menu.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.menu.edit', $menu->slug) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gold-500 rounded-xl hover:bg-gold-600 transition-colors">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="h-64 sm:h-80 bg-slate-100">
                @if($menu->gambar)
                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image text-6xl"></i></div>
                @endif
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between gap-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                        <i class="fa-solid fa-tag"></i> {{ $menu->kategori->nama_kategori ?? 'Tanpa kategori' }}
                    </span>
                    @if($menu->is_active)
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Informasi Hidangan</p>
            <div class="grid grid-cols-2 gap-4 mt-5">
                <div class="p-4 rounded-xl bg-gold-50 border border-gold-100">
                    <p class="text-xs text-gold-700">Harga</p>
                    <p class="mt-1 text-xl font-bold text-gold-700">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <p class="text-xs text-slate-500">Stok Tersedia</p>
                    <p class="mt-1 text-xl font-bold {{ $menu->stok < 5 ? 'text-red-600' : 'text-charcoal-900' }}">{{ $menu->stok }}</p>
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Deskripsi</p>
                <p class="mt-2 text-sm leading-6 text-slate-600 whitespace-pre-line">{{ $menu->deskripsi ?: 'Belum ada deskripsi untuk menu ini.' }}</p>
            </div>
            <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><p class="text-xs text-slate-400">Slug</p><p class="mt-1 font-mono text-slate-700 break-all">{{ $menu->slug }}</p></div>
                <div><p class="text-xs text-slate-400">Dibuat</p><p class="mt-1 text-slate-700">{{ $menu->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</p></div>
            </div>
        </div>
    </div>
</div>
@endsection
