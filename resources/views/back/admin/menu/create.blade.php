@extends('template.back_admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-plus text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Tambah Menu</h1>
            <p class="text-sm text-slate-500 mt-1">Tambahkan hidangan baru ke dalam daftar menu restoran.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nama_menu" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu') }}" required placeholder="Contoh: Kepiting Saus Padang" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('nama_menu') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-charcoal-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <div x-data="searchableRelationSelect(@js($kategori->map(fn ($kat) => ['id' => $kat->id, 'label' => $kat->nama_kategori])->values()), @js((string) old('kategori_id')))" @click.outside="open = false" class="relative">
                        <input type="hidden" name="kategori_id" :value="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20">
                            <span x-text="selectedLabel() || '-- Pilih Kategori --'" :class="selected ? 'text-charcoal-900' : 'text-slate-400'"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                            <div class="border-b border-slate-100 p-2">
                                <div class="relative flex items-center">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 text-xs text-slate-400 pointer-events-none"></i>
                                    <input type="text" x-model="search" placeholder="Cari kategori..." class="w-full rounded-lg border border-slate-200 bg-white py-1.5 pl-8 pr-8 text-xs sm:text-sm text-charcoal-900 placeholder:text-slate-400 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 outline-none">
                                    <button type="button" x-show="search && search.length > 0" @click="search = ''" class="absolute right-2.5 text-slate-400 hover:text-slate-600 p-0.5" style="display: none;">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto p-1 custom-scrollbar">
                                <button type="button" x-show="filteredOptions().length === 0" class="w-full px-3 py-3 text-left text-sm text-slate-400" disabled>Tidak ada kategori ditemukan.</button>
                                <template x-for="option in filteredOptions()" :key="option.id">
                                    <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 hover:bg-gold-50 hover:text-gold-700">
                                        <span x-text="option.label"></span>
                                        <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    @error('kategori_id') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-semibold text-charcoal-900 mb-2">Foto Menu</label>
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                    @error('gambar') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="harga" class="block text-sm font-semibold text-charcoal-900 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga', 0) }}" min="0" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('harga') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-semibold text-charcoal-900 mb-2">Stok Awal <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" min="0" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('stok') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-charcoal-900 mb-2">Deskripsi Singkat</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 flex items-center mt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-gold-500 bg-slate-100 border-slate-300 rounded focus:ring-gold-500 focus:ring-2">
                    <label for="is_active" class="ml-3 text-sm font-medium text-charcoal-900">Aktifkan Menu Ini (Tampil di web pelanggan)</label>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.menu.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition-colors focus:ring-4 focus:ring-slate-100">Batal</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        function register() {
            if (window.Alpine && typeof window.searchableRelationSelect === 'function') {
                window.Alpine.data('searchableRelationSelect', window.searchableRelationSelect);
            }
        }
        if (window.Alpine) {
            register();
        } else {
            document.addEventListener('alpine:init', register);
        }
    })();
</script>
@endsection
