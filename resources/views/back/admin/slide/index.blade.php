@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
                <i class="fa-solid fa-images text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Slide Banner</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola banner promosi untuk halaman utama website.</p>
            </div>
        </div>
        <a href="{{ route('admin.slide.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
            <i class="fa-solid fa-plus"></i> Tambah Banner
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium animate-fade-in-down">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 lg:py-5">Banner & Judul</th>
                        <th class="px-6 py-4 lg:py-5">Tautan Menu</th>
                        <th class="px-6 py-4 lg:py-5">Status</th>
                        <th class="px-6 py-4 lg:py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($slide as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-24 h-14 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-charcoal-900">{{ $item->judul }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($item->sub_judul, 40) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->menu_id && $item->menu)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-600 border border-blue-200">
                                        <i class="fa-solid fa-link"></i> {{ $item->menu->nama_menu }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 italic">Tanpa Tautan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('admin.slide.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-gold-600 hover:bg-gold-50 transition-all duration-200">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.slide.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus banner ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                    <i class="fa-solid fa-images text-4xl text-slate-300"></i>
                                    <p class="text-base font-medium">Belum ada data banner slide.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($slide->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $slide->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
