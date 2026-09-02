@extends('template.back_admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-pen-to-square text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Edit Banner</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi, gambar, atau tautan pada banner ini.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <form action="{{ route('admin.slide.update', $slide->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="judul" class="block text-sm font-semibold text-charcoal-900 mb-2">Judul Utama <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $slide->judul) }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('judul') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="sub_judul" class="block text-sm font-semibold text-charcoal-900 mb-2">Sub Judul (Opsional)</label>
                    <input type="text" name="sub_judul" id="sub_judul" value="{{ old('sub_judul', $slide->sub_judul) }}" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('sub_judul') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-semibold text-charcoal-900 mb-2">Ganti Gambar (Opsional)</label>
                    <div class="flex items-center gap-3">
                        @if($slide->gambar)
                            <img src="{{ asset('storage/' . $slide->gambar) }}" class="w-16 h-10 rounded-lg object-cover border border-slate-200 shrink-0" alt="Current Image">
                        @endif
                        <input type="file" name="gambar" id="gambar" accept="image/*" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-3 py-2 transition-all duration-200 hover:bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                    </div>
                    @error('gambar') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="menu_id" class="block text-sm font-semibold text-charcoal-900 mb-2">Tautkan ke Menu (Opsional)</label>
                    <select name="menu_id" id="menu_id" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                        <option value="">-- Tidak Ditautkan --</option>
                        @foreach($menu as $item)
                            <option value="{{ $item->id }}" {{ old('menu_id', $slide->menu_id) == $item->id ? 'selected' : '' }}>{{ $item->nama_menu }}</option>
                        @endforeach
                    </select>
                    @error('menu_id') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-charcoal-900 mb-2">Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" rows="2" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">{{ old('deskripsi', $slide->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 flex items-center mt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }} class="w-5 h-5 text-gold-500 bg-slate-100 border-slate-300 rounded focus:ring-gold-500 focus:ring-2">
                    <label for="is_active" class="ml-3 text-sm font-medium text-charcoal-900">Tampilkan Banner Ini</label>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.slide.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition-colors focus:ring-4 focus:ring-slate-100">Batal</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
