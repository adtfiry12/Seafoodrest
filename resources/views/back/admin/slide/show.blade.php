@extends('template.back_admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <i class="fa-solid fa-images text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Banner</p>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $slide->judul }}</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.slide.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.slide.edit', $slide->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gold-500 rounded-xl hover:bg-gold-600 transition-colors">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="h-56 sm:h-72 bg-slate-100">
                @if($slide->gambar)
                    <img src="{{ asset('storage/' . $slide->gambar) }}" alt="{{ $slide->judul }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300"><i class="fa-solid fa-image text-6xl"></i></div>
                @endif
            </div>
            <div class="p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-serif text-lg font-bold text-charcoal-900">{{ $slide->judul }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ $slide->sub_judul ?: 'Tidak ada sub judul' }}</p>
                    </div>
                    @if($slide->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-600 border border-red-200"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Informasi Banner</p>
            <div class="mt-5 space-y-5">
                <div>
                    <p class="text-xs text-slate-400">Tautan Menu</p>
                    @if($slide->menu)
                        <a href="{{ route('admin.menu.show', $slide->menu->slug) }}" class="mt-1 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"><i class="fa-solid fa-link"></i> {{ $slide->menu->nama_menu }}</a>
                    @else
                        <p class="mt-1 text-sm italic text-slate-500">Tidak ditautkan ke menu</p>
                    @endif
                </div>
                <div class="pt-5 border-t border-slate-100">
                    <p class="text-xs text-slate-400">Deskripsi</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600 whitespace-pre-line">{{ $slide->deskripsi ?: 'Belum ada deskripsi untuk banner ini.' }}</p>
                </div>
                <div class="pt-5 border-t border-slate-100">
                    <p class="text-xs text-slate-400">Dibuat</p>
                    <p class="mt-1 text-sm text-slate-700">{{ $slide->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
