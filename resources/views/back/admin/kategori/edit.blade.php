@extends('template.back_admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-pen-to-square text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Edit Kategori</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi kategori menu hidangan Anda.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_kategori" class="block text-sm font-semibold text-charcoal-900 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_kategori" id="nama_kategori"
                    value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required
                    placeholder="Contoh: Kepiting & Lobster"
                    class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 lg:py-3.5 transition-all duration-200 hover:bg-white">
                @error('nama_kategori')
                    <p class="flex items-center gap-1 text-xs text-red-500 mt-2 font-medium">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-semibold text-charcoal-900 mb-2">Slug (Opsional)</label>
                <input type="text" name="slug" id="slug"
                    value="{{ old('slug', $kategori->slug) }}"
                    placeholder="kepiting-dan-lobster"
                    class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 lg:py-3.5 transition-all duration-200 hover:bg-white">
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> Kosongkan jika ingin dibuat otomatis mengikuti nama kategori.
                </p>
                @error('slug')
                    <p class="flex items-center gap-1 text-xs text-red-500 mt-2 font-medium">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.kategori.index') }}"
                   class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition-colors focus:ring-4 focus:ring-slate-100">
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
