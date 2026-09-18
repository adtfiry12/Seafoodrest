@extends('template.back_admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <i class="fa-solid fa-tags text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Kategori</p>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $kategori->nama_kategori }}</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.kategori.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.kategori.edit', $kategori->slug) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gold-500 rounded-xl hover:bg-gold-600 transition-colors">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Menu</p>
            <p class="mt-2 text-3xl font-bold text-charcoal-900">{{ $kategori->menu->count() }}</p>
            <p class="mt-1 text-sm text-slate-500">Menu dalam kategori ini</p>
        </div>
        <div class="md:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Slug Kategori</p>
            <p class="mt-2 font-mono text-sm text-charcoal-900 break-all">{{ $kategori->slug }}</p>
            <p class="mt-2 text-sm text-slate-500">Dibuat {{ $kategori->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-5 sm:px-6 py-5 border-b border-slate-100">
            <h2 class="font-serif text-lg font-bold text-charcoal-900">Menu Dalam Kategori</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar hidangan yang terhubung dengan kategori ini.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($kategori->menu as $item)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-5 sm:px-6 py-4 hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="fa-solid fa-utensils"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-charcoal-900">{{ $item->nama_menu }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">Rp {{ number_format($item->harga, 0, ',', '.') }} · Stok {{ $item->stok }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.menu.show', $item->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Lihat detail <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-utensils text-3xl text-slate-300"></i>
                    <p class="mt-3 text-sm font-medium">Belum ada menu dalam kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
